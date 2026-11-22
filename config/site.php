<?php

/*
|--------------------------------------------------------------------------
| Website content defaults
|--------------------------------------------------------------------------
| This is the content the website shipped with. The admin can change any of it
| from Dashboard > Site Settings (stored in the `site_settings` table).
| Anything the admin has not changed falls back to the value here.
|
| Texts marked "markdown" support: ### headings, - bullets, **bold**, [text](url),
| plus the {email} and {phone} placeholders (replaced with the contact settings).
*/

return [

    'social_platforms' => [
        'x'         => ['icon' => 'bi-twitter-x',  'label' => 'X (Twitter)'],
        'instagram' => ['icon' => 'bi-instagram',  'label' => 'Instagram'],
        'facebook'  => ['icon' => 'bi-facebook',   'label' => 'Facebook'],
        'tiktok'    => ['icon' => 'bi-tiktok',     'label' => 'TikTok'],
        'snapchat'  => ['icon' => 'bi-snapchat',   'label' => 'Snapchat'],
        'youtube'   => ['icon' => 'bi-youtube',    'label' => 'YouTube'],
        'linkedin'  => ['icon' => 'bi-linkedin',   'label' => 'LinkedIn'],
        'whatsapp'  => ['icon' => 'bi-whatsapp',   'label' => 'WhatsApp'],
    ],

    'defaults' => [

        // ---------------- Branding ----------------
        'brand.logo'         => null, // null = the default star icon
        'brand.name_ar'      => 'منصة عروض في المملكة العربية السعودية', // header + footer title
        'brand.name_en'      => 'Offers Platform in Saudi Arabia',
        'brand.site_name_ar' => 'منصة العروض داخل السعودية', // browser title + copyright
        'brand.site_name_en' => 'Offers Platform in Saudi Arabia',
        'brand.app_name_ar'  => 'أُورود بلس', // support & privacy page
        'brand.app_name_en'  => 'Orood Plus',

        // ---------------- Contact ----------------
        'contact.email' => 'oroodapplication@gmail.com',
        'contact.phone' => '+966 50 000 0000',

        // ---------------- Social links ('' = hidden) ----------------
        'social.x'         => '#',
        'social.instagram' => '#',
        'social.facebook'  => '#',
        'social.tiktok'    => '',
        'social.snapchat'  => '',
        'social.youtube'   => '',
        'social.linkedin'  => '',
        'social.whatsapp'  => '',

        // ---------------- Support page ----------------
        'legal.last_updated' => '2026-09-08',

        'support.intro_ar' => 'نحن هنا لمساعدتك. إذا واجهت أي مشكلة أثناء استخدام تطبيق "أُورود بلس"، أو كان لديك استفسار بخصوص حسابك، الاشتراكات، العروض، أو المدفوعات، يمكنك التواصل معنا عبر أي من القنوات التالية وسنقوم بالرد خلال يوم عمل واحد.',
        'support.intro_en' => "We're here to help. If you run into an issue while using the Orood Plus app, or have a question about your account, subscriptions, offers, or payments, reach out through any of the channels below and we'll get back to you within one business day.",

        'support.faq' => [
            [
                'q_ar' => 'كيف يمكنني حذف حسابي؟',
                'q_en' => 'How do I delete my account?',
                'a_ar' => 'يمكنك طلب حذف حسابك وبياناتك بشكل نهائي عبر التواصل معنا على {email} من نفس البريد الإلكتروني المسجل في حسابك، وسيتم تنفيذ الطلب خلال 7 أيام عمل.',
                'a_en' => 'You can request permanent deletion of your account and data by emailing us at {email} from the email address registered on your account. Requests are processed within 7 business days.',
            ],
            [
                'q_ar' => 'كيف يمكنني إلغاء اشتراك مزوّد الخدمة؟',
                'q_en' => 'How do I cancel a provider subscription?',
                'a_ar' => 'من داخل التطبيق انتقل إلى "حسابي" ثم "الاشتراكات"، ويمكنك إيقاف التجديد التلقائي من هناك. سيبقى اشتراكك فعّالاً حتى نهاية الفترة المدفوعة.',
                'a_en' => 'Inside the app, go to "Account" then "Subscriptions" to turn off auto-renewal. Your subscription remains active until the end of the current paid period.',
            ],
            [
                'q_ar' => 'لم يصلني رمز التحقق (OTP)، ماذا أفعل؟',
                'q_en' => "I didn't receive my OTP code, what should I do?",
                'a_ar' => 'تأكد من صحة البريد الإلكتروني أو رقم الجوال المسجل، وتحقق من مجلد الرسائل غير المرغوب فيها. إذا استمرت المشكلة تواصل مع فريق الدعم.',
                'a_en' => 'Make sure your registered email or phone number is correct, and check your spam folder. If the issue persists, contact our support team.',
            ],
        ],

        // ---------------- Privacy policy (markdown) ----------------
        'privacy.body_ar' => <<<'MD'
توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية بياناتك عند استخدامك لتطبيق وموقع "أُورود بلس" ("التطبيق"، "نحن"، "المنصة")، المخصص لعرض العروض الخاصة للأماكن داخل المملكة العربية السعودية حسب المدينة والتصنيف، لكل من العملاء ومزوّدي الخدمة.

### 1. البيانات التي نجمعها

- بيانات الحساب: الاسم، البريد الإلكتروني، رقم الجوال، وكلمة المرور (مشفّرة).
- بيانات مزوّدي الخدمة: اسم المنشأة، الرقم الضريبي، السجل التجاري، ورقم الجوال/واتساب.
- بيانات الموقع الجغرافي: المدينة والإحداثيات (خط الطول والعرض) لعرض العروض القريبة منك وتحديد موقع منشأة مزوّد الخدمة.
- الصور: صورة الملف الشخصي وصور العروض التي يرفعها مزوّدو الخدمة.
- بيانات الاستخدام: العروض المفضّلة، التفاعل مع العروض، والبلاغات/الشكاوى المُقدّمة.
- بيانات الدفع: تتم معالجة معلومات الدفع مباشرة عبر بوابات دفع خارجية معتمدة (Geidea وMoyasar)، ولا نقوم بتخزين بيانات بطاقتك البنكية على خوادمنا.
- معرّف الإشعارات (FCM Token) لإرسال الإشعارات عبر خدمة Firebase.

### 2. كيفية استخدام بياناتك

- إنشاء وإدارة حسابك، والتحقق من هويتك عبر رمز التحقق (OTP) أو رمز PIN.
- عرض العروض المناسبة لمدينتك واهتماماتك.
- تمكين مزوّدي الخدمة من إضافة وتعديل عروضهم وإدارة اشتراكاتهم.
- معالجة المدفوعات والاشتراكات.
- إرسال إشعارات متعلقة بالعروض والحساب والمدفوعات.
- التعامل مع البلاغات والشكاوى المتعلقة بالعروض.
- تحسين خدماتنا وتحليل استخدام التطبيق بشكل عام.

### 3. مشاركة البيانات مع أطراف ثالثة

لا نبيع بياناتك الشخصية لأي طرف ثالث. قد تتم مشاركة بيانات محدودة مع:

- بوابات الدفع (Geidea, Moyasar) لإتمام عمليات الدفع.
- خدمة Firebase (Google) لإرسال الإشعارات الفورية.
- الجهات الحكومية المختصة إذا طُلب ذلك بموجب نظام معمول به في المملكة العربية السعودية.

### 4. الاحتفاظ بالبيانات

نحتفظ ببياناتك طوال فترة نشاط حسابك، ولمدة إضافية بعد الحذف إذا اقتضت ذلك التزامات نظامية أو محاسبية (مثل سجلات المدفوعات)، ثم يتم حذفها أو إخفاء هويتها بشكل آمن.

### 5. حقوقك

- الوصول إلى بياناتك الشخصية وتعديلها من داخل التطبيق.
- طلب حذف حسابك وبياناتك بشكل نهائي.
- إيقاف تفعيل حسابك مؤقتًا.
- الانسحاب من تلقي الإشعارات التسويقية في أي وقت.

### 6. أمان البيانات

نستخدم إجراءات تقنية وتنظيمية معقولة لحماية بياناتك، بما في ذلك تشفير كلمات المرور وربط الوصول إلى بيانات الحساب بمصادقة آمنة. مع ذلك، لا يمكن ضمان الأمان المطلق لأي نقل بيانات عبر الإنترنت.

### 7. خصوصية الأطفال

لا يستهدف التطبيق الأطفال دون سن 13 عامًا، ولا نقوم عمدًا بجمع بيانات من هذه الفئة العمرية.

### 8. التعديلات على هذه السياسة

قد نقوم بتحديث سياسة الخصوصية من وقت لآخر. سيتم نشر أي تغييرات جوهرية على هذه الصفحة مع تاريخ التحديث.

### 9. تواصل معنا

لأي استفسار بخصوص هذه السياسة أو بياناتك، راسلنا على {email}.
MD,

        'privacy.body_en' => <<<'MD'
This Privacy Policy explains how we collect, use, and protect your data when you use the Orood Plus app and website ("the App", "we", "the Platform"), a platform for special offers at places across Saudi Arabia, organized by city and category, for both customers and service providers.

### 1. Information We Collect

- Account data: name, email, phone number, and password (hashed).
- Service provider data: business name, tax number, commercial registration, and WhatsApp/phone number.
- Location data: city and coordinates (latitude/longitude), used to show nearby offers and locate the provider's place of business.
- Images: profile photo and offer images uploaded by service providers.
- Usage data: favorite offers, offer interactions, and submitted reports/complaints.
- Payment data: payments are processed directly by our licensed payment gateways (Geidea and Moyasar); we do not store your card details on our servers.
- Push notification identifier (FCM Token) used to deliver notifications via Firebase.

### 2. How We Use Your Data

- Creating and managing your account, and verifying your identity via OTP or PIN code.
- Showing offers relevant to your city and interests.
- Allowing service providers to add and manage their offers and subscriptions.
- Processing payments and subscriptions.
- Sending notifications related to offers, your account, and payments.
- Handling reports and complaints related to offers.
- Improving our services and analyzing overall app usage.

### 3. Sharing Your Data

We do not sell your personal data to any third party. Limited data may be shared with:

- Payment gateways (Geidea, Moyasar) to complete payment transactions.
- Firebase (Google) to deliver push notifications.
- Relevant government authorities if required by applicable law in Saudi Arabia.

### 4. Data Retention

We retain your data for as long as your account is active, and for an additional period after deletion where required by legal or accounting obligations (such as payment records), after which it is securely deleted or anonymized.

### 5. Your Rights

- Access and update your personal data from within the app.
- Request permanent deletion of your account and data.
- Temporarily deactivate your account.
- Opt out of marketing notifications at any time.

### 6. Data Security

We use reasonable technical and organizational measures to protect your data, including password hashing and authenticated access to account data. However, no method of transmission over the internet can be guaranteed to be 100% secure.

### 7. Children's Privacy

The App is not directed at children under the age of 13, and we do not knowingly collect data from this age group.

### 8. Changes to This Policy

We may update this Privacy Policy from time to time. Any material changes will be posted on this page along with the updated date.

### 9. Contact Us

For any questions about this policy or your data, contact us at {email}.
MD,
    ],
];
