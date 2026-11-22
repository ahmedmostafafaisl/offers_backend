<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Dashboard > Site Settings: edit the website content that used to be hard-coded.
 */
class SiteSettingsController extends Controller
{
    /** Tab => the settings keys it owns (used by "Restore defaults"). */
    private const GROUPS = [
        'branding' => [
            'brand.logo',
            'brand.name_ar', 'brand.name_en',
            'brand.site_name_ar', 'brand.site_name_en',
            'brand.app_name_ar', 'brand.app_name_en',
        ],
        'contact' => ['contact.email', 'contact.phone'],
        'social' => [
            'social.x', 'social.instagram', 'social.facebook', 'social.tiktok',
            'social.snapchat', 'social.youtube', 'social.linkedin', 'social.whatsapp',
        ],
        'support' => ['support.intro_ar', 'support.intro_en', 'support.faq', 'legal.last_updated'],
        'privacy' => ['privacy.body_ar', 'privacy.body_en', 'legal.last_updated'],
    ];

    public function __construct(private SiteSettings $site)
    {
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'branding');
        if (!array_key_exists($tab, self::GROUPS) && $tab !== 'featured') {
            $tab = 'branding';
        }

        return view('dashboard.settings.index', [
            'tab' => $tab,
            'platforms' => config('site.social_platforms'),
            'faq' => $this->site->faq(),
            'featuredOffers' => \App\Models\Offer::query()->where('is_featured', true)
                ->orderBy('featured_order')->orderByDesc('id')->get(['id', 'name', 'featured_order']),
            'featuredCategories' => \App\Models\Category::query()->where('is_featured', true)
                ->orderBy('featured_order')->orderByDesc('id')->get(['id', 'name', 'featured_order']),
        ]);
    }

    public function update(Request $request, string $group)
    {
        abort_unless(array_key_exists($group, self::GROUPS), 404);

        match ($group) {
            'branding' => $this->saveBranding($request),
            'contact' => $this->saveContact($request),
            'social' => $this->saveSocial($request),
            'support' => $this->saveSupport($request),
            'privacy' => $this->savePrivacy($request),
        };

        return redirect()
            ->route('dashboard.settings.index', ['tab' => $group])
            ->with('success', __('site.saved'));
    }

    public function reset(string $group)
    {
        abort_unless(array_key_exists($group, self::GROUPS), 404);

        if ($group === 'branding') {
            $this->deleteLogoFile();
        }

        $this->site->forget(self::GROUPS[$group]);

        if (in_array($group, ['support', 'privacy'], true)) {
            $this->touchLastUpdated();
        }

        return redirect()
            ->route('dashboard.settings.index', ['tab' => $group])
            ->with('success', __('site.restored'));
    }

    // ------------------------------------------------------------------ groups

    private function saveBranding(Request $request): void
    {
        $data = $request->validate([
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'site_name_ar' => ['nullable', 'string', 'max:255'],
            'site_name_en' => ['nullable', 'string', 'max:255'],
            'app_name_ar' => ['nullable', 'string', 'max:255'],
            'app_name_en' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteLogoFile();
            $this->site->set('brand.logo', $request->file('logo')->store('branding', 'public'));
        } elseif (!empty($data['remove_logo'])) {
            $this->deleteLogoFile();
            $this->site->forget('brand.logo'); // back to the default icon
        }

        foreach (['name', 'site_name', 'app_name'] as $field) {
            foreach (['ar', 'en'] as $lang) {
                $this->setOrDefault("brand.{$field}_{$lang}", $data["{$field}_{$lang}"] ?? null);
            }
        }
    }

    private function saveContact(Request $request): void
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // blank = hide it on the website
        $this->site->set('contact.email', trim($data['email'] ?? ''));
        $this->site->set('contact.phone', trim($data['phone'] ?? ''));
    }

    private function saveSocial(Request $request): void
    {
        $rules = [];
        foreach (array_keys(config('site.social_platforms')) as $platform) {
            // a full link, or "#" (placeholder icon that goes nowhere)
            $rules[$platform] = ['nullable', 'string', 'max:255', 'regex:/^(#|https?:\/\/\S+)$/i'];
        }

        $data = $request->validate($rules, [
            'regex' => __('site.social_invalid'),
        ]);

        foreach (array_keys(config('site.social_platforms')) as $platform) {
            // blank = hide that icon on the website
            $this->site->set("social.{$platform}", trim($data[$platform] ?? ''));
        }
    }

    private function saveSupport(Request $request): void
    {
        $data = $request->validate([
            'intro_ar' => ['nullable', 'string', 'max:5000'],
            'intro_en' => ['nullable', 'string', 'max:5000'],
            'faq' => ['nullable', 'array', 'max:30'],
            'faq.*.q_ar' => ['nullable', 'string', 'max:500'],
            'faq.*.q_en' => ['nullable', 'string', 'max:500'],
            'faq.*.a_ar' => ['nullable', 'string', 'max:3000'],
            'faq.*.a_en' => ['nullable', 'string', 'max:3000'],
        ]);

        $faq = [];
        foreach ($data['faq'] ?? [] as $i => $row) {
            $row = array_map(fn($v) => trim((string) $v), $row);

            if (implode('', $row) === '') {
                continue; // empty row: ignore
            }

            foreach (['q_ar', 'q_en', 'a_ar', 'a_en'] as $field) {
                if (($row[$field] ?? '') === '') {
                    throw ValidationException::withMessages([
                        "faq.{$i}.{$field}" => __('site.faq_incomplete'),
                    ]);
                }
            }

            $faq[] = [
                'q_ar' => $row['q_ar'], 'q_en' => $row['q_en'],
                'a_ar' => $row['a_ar'], 'a_en' => $row['a_en'],
            ];
        }

        $this->setOrDefault('support.intro_ar', $data['intro_ar'] ?? null);
        $this->setOrDefault('support.intro_en', $data['intro_en'] ?? null);

        // an empty list is kept on purpose: it hides the FAQ section
        $this->site->set('support.faq', json_encode($faq, JSON_UNESCAPED_UNICODE));

        $this->touchLastUpdated();
    }

    private function savePrivacy(Request $request): void
    {
        $data = $request->validate([
            'body_ar' => ['nullable', 'string', 'max:30000'],
            'body_en' => ['nullable', 'string', 'max:30000'],
        ]);

        $this->setOrDefault('privacy.body_ar', $data['body_ar'] ?? null);
        $this->setOrDefault('privacy.body_en', $data['body_en'] ?? null);

        $this->touchLastUpdated();
    }

    // ----------------------------------------------------------------- helpers

    /** Blank input = use the shipped default (the row is removed). */
    private function setOrDefault(string $key, ?string $value): void
    {
        $value = $value === null ? '' : trim(str_replace("\r\n", "\n", $value));

        $value === '' ? $this->site->forget($key) : $this->site->set($key, $value);
    }

    private function touchLastUpdated(): void
    {
        $this->site->set('legal.last_updated', now()->toDateString());
    }

    private function deleteLogoFile(): void
    {
        // only a file the admin uploaded (an override), never the default
        if ($this->site->isOverridden('brand.logo') && $path = $this->site->get('brand.logo')) {
            Storage::disk('public')->delete($path);
        }
    }
}
