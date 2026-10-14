@extends('layouts.landing')

@section('title', app()->getLocale() === 'ar'
    ? 'أُورود بلس - الدعم وسياسة الخصوصية'
    : 'Orood Plus - Support & Privacy Policy')

@section('body')
    @php
        $isAr = app()->getLocale() === 'ar';
        $lastUpdated = '2026-09-08';
    @endphp

    {{-- Simple header --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;background:#efeaff;color:#f4c430;">
                    <i class="bi bi-stars"></i>
                </span>
                <span>{{ $isAr ? 'أُورود بلس' : 'Orood Plus' }}</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-{{ $isAr ? 'end' : 'left' }}"></i>
                    {{ $isAr ? 'الرئيسية' : 'Home' }}
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero py-5">
        <div class="container">
            <h1 class="fw-bold mb-2">{{ $isAr ? 'الدعم وسياسة الخصوصية' : 'Support & Privacy Policy' }}</h1>
            <p class="text-muted mb-0">
                {{ $isAr ? 'تطبيق أُورود بلس' : 'Orood Plus Application' }} &middot;
                {{ $isAr ? 'آخر تحديث: ' . $lastUpdated : 'Last updated: ' . $lastUpdated }}
            </p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            {{-- Side navigation --}}
            <div class="col-lg-3 mb-4">
                <div class="list-group sticky-top" style="top: 20px;">
                    <a href="#support" class="list-group-item list-group-item-action">
                        <i class="bi bi-headset {{ $isAr ? 'ms-2' : 'me-2' }}"></i>{{ $isAr ? 'الدعم' : 'Support' }}
                    </a>
                    <a href="#privacy" class="list-group-item list-group-item-action">
                        <i class="bi bi-shield-check {{ $isAr ? 'ms-2' : 'me-2' }}"></i>{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-lg-9">

                {{-- ============ SUPPORT ============ --}}
                <section id="support" class="mb-5">
                    <h2 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-headset badge-soft rounded-circle p-2 {{ $isAr ? 'ms-2' : 'me-2' }}"></i>
                        {{ $isAr ? 'الدعم الفني' : 'Support' }}
                    </h2>

                    @if($isAr)
                        <p>
                            نحن هنا لمساعدتك. إذا واجهت أي مشكلة أثناء استخدام تطبيق "أُورود بلس"، أو كان لديك
                            استفسار بخصوص حسابك، الاشتراكات، العروض، أو المدفوعات، يمكنك التواصل معنا عبر أي من
                            القنوات التالية وسنقوم بالرد خلال يوم عمل واحد.
                        </p>
                    @else
                        <p>
                            We're here to help. If you run into an issue while using the Orood Plus app, or have a
                            question about your account, subscriptions, offers, or payments, reach out through any of
                            the channels below and we'll get back to you within one business day.
                        </p>
                    @endif

                    <div class="row g-3 my-3">
                        <div class="col-md-6">
                            <div class="card-soft p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="icon-bubble rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <strong>{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</strong>
                                </div>
                                <a href="mailto:oroodapplication@gmail.com" class="text-decoration-none">
                                    oroodapplication@gmail.com
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-soft p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="icon-bubble rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <strong>{{ $isAr ? 'الهاتف / واتساب' : 'Phone / WhatsApp' }}</strong>
                                </div>
                                <span class="text-muted" dir="ltr">+966 50 000 0000</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4">{{ $isAr ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}</h5>
                    <div class="accordion mt-2" id="faqAccordion">

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    {{ $isAr ? 'كيف يمكنني حذف حسابي؟' : 'How do I delete my account?' }}
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    @if($isAr)
                                        يمكنك طلب حذف حسابك وبياناتك بشكل نهائي عبر التواصل معنا على
                                        <a href="mailto:oroodapplication@gmail.com">oroodapplication@gmail.com</a>
                                        من نفس البريد الإلكتروني المسجل في حسابك، وسيتم تنفيذ الطلب خلال 7 أيام عمل.
                                    @else
                                        You can request permanent deletion of your account and data by emailing us at
                                        <a href="mailto:oroodapplication@gmail.com">oroodapplication@gmail.com</a>
                                        from the email address registered on your account. Requests are processed
                                        within 7 business days.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    {{ $isAr ? 'كيف يمكنني إلغاء اشتراك مزوّد الخدمة؟' : 'How do I cancel a provider subscription?' }}
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    @if($isAr)
                                        من داخل التطبيق انتقل إلى "حسابي" ثم "الاشتراكات"، ويمكنك إيقاف التجديد
                                        التلقائي من هناك. سيبقى اشتراكك فعّالاً حتى نهاية الفترة المدفوعة.
                                    @else
                                        Inside the app, go to "Account" then "Subscriptions" to turn off
                                        auto-renewal. Your subscription remains active until the end of the
                                        current paid period.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    {{ $isAr ? 'لم يصلني رمز التحقق (OTP)، ماذا أفعل؟' : "I didn't receive my OTP code, what should I do?" }}
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    @if($isAr)
                                        تأكد من صحة البريد الإلكتروني أو رقم الجوال المسجل، وتحقق من مجلد الرسائل
                                        غير المرغوب فيها. إذا استمرت المشكلة تواصل مع فريق الدعم.
                                    @else
                                        Make sure your registered email or phone number is correct, and check your
                                        spam folder. If the issue persists, contact our support team.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ============ PRIVACY POLICY ============ --}}
                <section id="privacy">
                    <h2 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-shield-check badge-soft rounded-circle p-2 {{ $isAr ? 'ms-2' : 'me-2' }}"></i>
                        {{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                    </h2>

                    @if($isAr)
                        <p>
                            توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية بياناتك عند استخدامك لتطبيق وموقع
                            "أُورود بلس" ("التطبيق"، "نحن"، "المنصة")، المخصص لعرض العروض الخاصة للأماكن داخل
                            المملكة العربية السعودية حسب المدينة والتصنيف، لكل من العملاء ومزوّدي الخدمة.
                        </p>

                        <h5 class="fw-bold mt-4">1. البيانات التي نجمعها</h5>
                        <ul>
                            <li>بيانات الحساب: الاسم، البريد الإلكتروني، رقم الجوال، وكلمة المرور (مشفّرة).</li>
                            <li>بيانات مزوّدي الخدمة: اسم المنشأة، الرقم الضريبي، السجل التجاري، ورقم الجوال/واتساب.</li>
                            <li>بيانات الموقع الجغرافي: المدينة والإحداثيات (خط الطول والعرض) لعرض العروض القريبة منك وتحديد موقع منشأة مزوّد الخدمة.</li>
                            <li>الصور: صورة الملف الشخصي وصور العروض التي يرفعها مزوّدو الخدمة.</li>
                            <li>بيانات الاستخدام: العروض المفضّلة، التفاعل مع العروض، والبلاغات/الشكاوى المُقدّمة.</li>
                            <li>بيانات الدفع: تتم معالجة معلومات الدفع مباشرة عبر بوابات دفع خارجية معتمدة (Geidea وMoyasar)، ولا نقوم بتخزين بيانات بطاقتك البنكية على خوادمنا.</li>
                            <li>معرّف الإشعارات (FCM Token) لإرسال الإشعارات عبر خدمة Firebase.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">2. كيفية استخدام بياناتك</h5>
                        <ul>
                            <li>إنشاء وإدارة حسابك، والتحقق من هويتك عبر رمز التحقق (OTP) أو رمز PIN.</li>
                            <li>عرض العروض المناسبة لمدينتك واهتماماتك.</li>
                            <li>تمكين مزوّدي الخدمة من إضافة وتعديل عروضهم وإدارة اشتراكاتهم.</li>
                            <li>معالجة المدفوعات والاشتراكات.</li>
                            <li>إرسال إشعارات متعلقة بالعروض والحساب والمدفوعات.</li>
                            <li>التعامل مع البلاغات والشكاوى المتعلقة بالعروض.</li>
                            <li>تحسين خدماتنا وتحليل استخدام التطبيق بشكل عام.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">3. مشاركة البيانات مع أطراف ثالثة</h5>
                        <p>لا نبيع بياناتك الشخصية لأي طرف ثالث. قد تتم مشاركة بيانات محدودة مع:</p>
                        <ul>
                            <li>بوابات الدفع (Geidea, Moyasar) لإتمام عمليات الدفع.</li>
                            <li>خدمة Firebase (Google) لإرسال الإشعارات الفورية.</li>
                            <li>الجهات الحكومية المختصة إذا طُلب ذلك بموجب نظام معمول به في المملكة العربية السعودية.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">4. الاحتفاظ بالبيانات</h5>
                        <p>
                            نحتفظ ببياناتك طوال فترة نشاط حسابك، ولمدة إضافية بعد الحذف إذا اقتضت ذلك التزامات نظامية
                            أو محاسبية (مثل سجلات المدفوعات)، ثم يتم حذفها أو إخفاء هويتها بشكل آمن.
                        </p>

                        <h5 class="fw-bold mt-4">5. حقوقك</h5>
                        <ul>
                            <li>الوصول إلى بياناتك الشخصية وتعديلها من داخل التطبيق.</li>
                            <li>طلب حذف حسابك وبياناتك بشكل نهائي.</li>
                            <li>إيقاف تفعيل حسابك مؤقتًا.</li>
                            <li>الانسحاب من تلقي الإشعارات التسويقية في أي وقت.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">6. أمان البيانات</h5>
                        <p>
                            نستخدم إجراءات تقنية وتنظيمية معقولة لحماية بياناتك، بما في ذلك تشفير كلمات المرور
                            وربط الوصول إلى بيانات الحساب بمصادقة آمنة. مع ذلك، لا يمكن ضمان الأمان المطلق لأي
                            نقل بيانات عبر الإنترنت.
                        </p>

                        <h5 class="fw-bold mt-4">7. خصوصية الأطفال</h5>
                        <p>
                            لا يستهدف التطبيق الأطفال دون سن 13 عامًا، ولا نقوم عمدًا بجمع بيانات من هذه الفئة العمرية.
                        </p>

                        <h5 class="fw-bold mt-4">8. التعديلات على هذه السياسة</h5>
                        <p>
                            قد نقوم بتحديث سياسة الخصوصية من وقت لآخر. سيتم نشر أي تغييرات جوهرية على هذه الصفحة
                            مع تاريخ التحديث.
                        </p>

                        <h5 class="fw-bold mt-4">9. تواصل معنا</h5>
                        <p>
                            لأي استفسار بخصوص هذه السياسة أو بياناتك، راسلنا على
                            <a href="mailto:oroodapplication@gmail.com">oroodapplication@gmail.com</a>.
                        </p>
                    @else
                        <p>
                            This Privacy Policy explains how we collect, use, and protect your data when you use the
                            Orood Plus app and website ("the App", "we", "the Platform"), a platform for special
                            offers at places across Saudi Arabia, organized by city and category, for both customers
                            and service providers.
                        </p>

                        <h5 class="fw-bold mt-4">1. Information We Collect</h5>
                        <ul>
                            <li>Account data: name, email, phone number, and password (hashed).</li>
                            <li>Service provider data: business name, tax number, commercial registration, and WhatsApp/phone number.</li>
                            <li>Location data: city and coordinates (latitude/longitude), used to show nearby offers and locate the provider's place of business.</li>
                            <li>Images: profile photo and offer images uploaded by service providers.</li>
                            <li>Usage data: favorite offers, offer interactions, and submitted reports/complaints.</li>
                            <li>Payment data: payments are processed directly by our licensed payment gateways (Geidea and Moyasar); we do not store your card details on our servers.</li>
                            <li>Push notification identifier (FCM Token) used to deliver notifications via Firebase.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">2. How We Use Your Data</h5>
                        <ul>
                            <li>Creating and managing your account, and verifying your identity via OTP or PIN code.</li>
                            <li>Showing offers relevant to your city and interests.</li>
                            <li>Allowing service providers to add and manage their offers and subscriptions.</li>
                            <li>Processing payments and subscriptions.</li>
                            <li>Sending notifications related to offers, your account, and payments.</li>
                            <li>Handling reports and complaints related to offers.</li>
                            <li>Improving our services and analyzing overall app usage.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">3. Sharing Your Data</h5>
                        <p>We do not sell your personal data to any third party. Limited data may be shared with:</p>
                        <ul>
                            <li>Payment gateways (Geidea, Moyasar) to complete payment transactions.</li>
                            <li>Firebase (Google) to deliver push notifications.</li>
                            <li>Relevant government authorities if required by applicable law in Saudi Arabia.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">4. Data Retention</h5>
                        <p>
                            We retain your data for as long as your account is active, and for an additional period
                            after deletion where required by legal or accounting obligations (such as payment
                            records), after which it is securely deleted or anonymized.
                        </p>

                        <h5 class="fw-bold mt-4">5. Your Rights</h5>
                        <ul>
                            <li>Access and update your personal data from within the app.</li>
                            <li>Request permanent deletion of your account and data.</li>
                            <li>Temporarily deactivate your account.</li>
                            <li>Opt out of marketing notifications at any time.</li>
                        </ul>

                        <h5 class="fw-bold mt-4">6. Data Security</h5>
                        <p>
                            We use reasonable technical and organizational measures to protect your data, including
                            password hashing and authenticated access to account data. However, no method of
                            transmission over the internet can be guaranteed to be 100% secure.
                        </p>

                        <h5 class="fw-bold mt-4">7. Children's Privacy</h5>
                        <p>
                            The App is not directed at children under the age of 13, and we do not knowingly collect
                            data from this age group.
                        </p>

                        <h5 class="fw-bold mt-4">8. Changes to This Policy</h5>
                        <p>
                            We may update this Privacy Policy from time to time. Any material changes will be posted
                            on this page along with the updated date.
                        </p>

                        <h5 class="fw-bold mt-4">9. Contact Us</h5>
                        <p>
                            For any questions about this policy or your data, contact us at
                            <a href="mailto:oroodapplication@gmail.com">oroodapplication@gmail.com</a>.
                        </p>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
