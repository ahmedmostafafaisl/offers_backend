<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Website content that the admin can edit from the dashboard.
 *
 * Every key has a default in config/site.php (the content the site shipped with).
 * The `site_settings` table only stores what the admin changed:
 *   - key missing in the table  => default is used
 *   - key present, value ''/NULL => admin cleared it on purpose (the item is hidden)
 */
class SiteSettings
{
    private const CACHE_KEY = 'site_settings.overrides';

    private ?array $overrides = null;

    public function defaults(): array
    {
        return config('site.defaults', []);
    }

    /** @return array<string, string|null> */
    private function overrides(): array
    {
        if ($this->overrides !== null) {
            return $this->overrides;
        }

        try {
            return $this->overrides = Cache::rememberForever(
                self::CACHE_KEY,
                fn() => SiteSetting::query()->pluck('value', 'key')->all()
            );
        } catch (\Throwable $e) {
            // e.g. the migration has not run yet: the site keeps working with the defaults
            return $this->overrides = [];
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $overrides = $this->overrides();

        if (array_key_exists($key, $overrides)) {
            return $overrides[$key] ?? '';
        }

        return $this->defaults()[$key] ?? $default;
    }

    public function isOverridden(string $key): bool
    {
        return array_key_exists($key, $this->overrides());
    }

    /** Localized text: t('brand.name') reads brand.name_ar or brand.name_en. */
    public function t(string $base): string
    {
        $locale = app()->getLocale() === 'ar' ? 'ar' : 'en';

        return (string) $this->get("{$base}_{$locale}", '');
    }

    public function set(string $key, ?string $value): void
    {
        SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        $this->flush();
    }

    /** Back to the default for these keys. */
    public function forget(string|array $keys): void
    {
        SiteSetting::query()->whereIn('key', (array) $keys)->delete();
        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->overrides = null;
    }

    public function logoUrl(): ?string
    {
        $path = $this->get('brand.logo');

        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }

    /** @return array<int, array{q_ar:string,q_en:string,a_ar:string,a_en:string}> */
    public function faq(): array
    {
        $faq = $this->get('support.faq', []);

        if (is_string($faq)) {
            $faq = json_decode($faq, true);
        }

        return is_array($faq) ? array_values($faq) : [];
    }

    /** Social links that have a URL. @return array<string, array{url:string,icon:string,label:string}> */
    public function socialLinks(): array
    {
        $out = [];

        foreach (config('site.social_platforms', []) as $platform => $meta) {
            $url = trim((string) $this->get("social.{$platform}", ''));

            if ($url !== '') {
                $out[$platform] = ['url' => $url, 'icon' => $meta['icon'], 'label' => $meta['label']];
            }
        }

        return $out;
    }

    /**
     * Markdown -> safe HTML (raw HTML in the text is stripped, unsafe links are blocked).
     * {email} and {phone} are replaced with the current contact details.
     */
    public function markdown(string $text): HtmlString
    {
        $email = trim((string) $this->get('contact.email', ''));
        $phone = trim((string) $this->get('contact.phone', ''));

        $text = strtr($text, [
            '{email}' => $email !== '' ? "[{$email}](mailto:{$email})" : '',
            '{phone}' => $phone,
        ]);

        return new HtmlString(Str::markdown($text, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }

    /** Localized markdown setting rendered to HTML, e.g. richText('privacy.body'). */
    public function richText(string $base): HtmlString
    {
        return $this->markdown($this->t($base));
    }
}
