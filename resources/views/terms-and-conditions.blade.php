@extends('layouts.main')
@php use App\Helpers\PhoneHelper; @endphp

@section('title', 'Terms and Conditions - ' . $companyData->company_name)
@section('meta_description', 'Read our terms and conditions to understand your rights and obligations when using ' .
    $companyData->company_name . ' services. Learn about our SMS service, website usage, and more.')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 bg-gray-900 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-black opacity-60"></div>
        </div>
        <div class="relative container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Terms and Conditions</h1>
            <p class="text-xl text-gray-300">Effective Date: January 24, 2025</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-yellow-500">Home</a>
                <span>/</span>
                <span class="text-yellow-500">Terms and Conditions</span>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="prose max-w-none">
                <h2 class="text-2xl font-bold mb-6">SMS Messaging Service Terms</h2>

                <h3 class="text-xl font-semibold mb-4">Consent to Receive Text Messages</h3>
                <p class="mb-6">
                    By checking the consent box on our forms, you agree to the following:
                    <br>
                    <em>"Yes, I would like to receive text messages from <span
                            class="font-semibold">{{ $companyData->company_name }}</span> with offers, appointment
                        reminders, and updates on roofing services. <span class="font-semibold">Messaging Frequency may
                            vary</span>. I understand that I can cancel my subscription at any time by replying <span
                            class="font-semibold">STOP</span>. Reply <span class="font-semibold">HELP
                            {{ PhoneHelper::format($companyData->phone) }}</span> for assistance. Message and data rates
                        apply. Information obtained as part of the SMS consent process will not be shared with third
                        parties. <a href="{{ route('privacy-policy') }}" target="_blank"
                            class="text-yellow-600 hover:text-yellow-700 underline">Privacy Policy</a> and <a
                            href="{{ route('terms-and-conditions') }}" target="_blank"
                            class="text-yellow-600 hover:text-yellow-700 underline">Terms of Service</a>."</em>
                </p>

                <h2 class="text-2xl font-bold mb-6">Interpretation and Definitions</h2>

                <h3 class="text-xl font-semibold mb-4">Interpretation</h3>
                <p class="mb-6">
                    Words in which the initial letter is capitalized have meanings defined under the following conditions.
                    The following definitions shall have the same meaning regardless of whether they appear in the singular
                    or plural.
                </p>

                <h3 class="text-xl font-semibold mb-4">Definitions</h3>
                <ul class="list-disc pl-6 mb-6">
                    <li><strong>Affiliate</strong> means an entity that controls, is controlled by or is under common
                        control with a party,
                        where "control" means ownership of 50% or more of the shares, equity interest or other securities
                        entitled to vote
                        for the election of directors or other managing authority.</li>
                    <li><strong>Country</strong> refers to: Texas, United States.</li>
                    <li><strong>Company</strong> (referred to as either "the Company", "We", "Us" or "Our" in this
                        Agreement) refers to
                        {{ $companyData->company_name }}, {{ $companyData->address }}.</li>
                    <li><strong>Device</strong> means any device that can access the Service such as a computer, a cellphone
                        or a digital tablet.</li>
                    <li><strong>Service</strong> refers to the Website and the SMS Messaging Service.</li>
                    <li><strong>Terms and Conditions</strong> (also referred to as "Terms") mean these Terms and Conditions
                        that form the
                        entire agreement between You and the Company regarding the use of the Service.</li>
                    <li><strong>Third-party Social Media Service</strong> means any services or content provided by a
                        third-party that may
                        be displayed, included or made available by the Service.</li>
                    <li><strong>Website</strong> refers to {{ $companyData->company_name }}, accessible from
                        https://vgeneralcontractors.com.</li>
                    <li><strong>You</strong> means the individual accessing or using the Service, or the company, or other
                        legal entity on
                        behalf of which such individual is accessing or using the Service, as applicable.</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">Acknowledgment</h2>
                <p class="mb-6">
                    These are the Terms and Conditions governing the use of this Service and the agreement that operates
                    between You and
                    the Company. These Terms and Conditions set out the rights and obligations of all users regarding the
                    use of the Service.
                </p>
                <p class="mb-6">
                    Your access to and use of the Service is conditioned on Your acceptance of and compliance with these
                    Terms and Conditions.
                    These Terms and Conditions apply to all visitors, users and others who access or use the Service.
                </p>
                <p class="mb-6">
                    By accessing or using the Service You agree to be bound by these Terms and Conditions. If You disagree
                    with any part
                    of these Terms and Conditions then You may not access the Service.
                </p>
                <p class="mb-6">
                    You represent that you are over the age of 18 years. The Company does not permit those under 18 to use
                    the Service.
                </p>

                <h2 class="text-2xl font-bold mb-6">2. Types of SMS Communications</h2>
                <p class="mb-4">If you have consented to receive text messages, you may receive SMS communications related
                    to the following:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li><strong>Customers and Guests:</strong> Updates regarding relevant information, including roof
                        inspection appointment reminders, roofing service scheduling confirmations, updates on the status of
                        your inspection, estimate, or roofing work, payment and billing notifications, special offers and
                        promotions for roofing services, and customer satisfaction surveys about roofing services.</li>
                    <li><strong>Job Applicants:</strong> Information about your application status, onboarding materials, or
                        other employment-related updates.</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">3. Frequency of SMS Messages</h2>
                <p class="mb-6">
                    The frequency of SMS messages may vary depending on your interactions with
                    {{ $companyData->company_name }}, the services you request, and your communication preferences. The
                    estimated frequency is 2-5 messages per client.
                </p>

                <h2 class="text-2xl font-bold mb-6">4. Message and Data Rates (SMS Messaging Service)</h2>
                <p class="mb-6">
                    Message and data rates may apply. {{ $companyData->company_name }} does not charge for sending SMS
                    messages, but standard messaging rates will be charged by your mobile service provider depending on your
                    rate plan. Check with your mobile service provider for details. You are responsible for all charges
                    associated with text messages you receive from us.
                </p>

                <h2 class="text-2xl font-bold mb-6">5. Opt-Out of SMS Messages</h2>
                <p class="mb-6">
                    To unsubscribe from {{ $companyData->company_name }}'s SMS messaging service at any time, reply STOP to
                    any SMS
                    message you receive from us.
                    Upon receiving your "STOP" message, we will send you a text message confirming that you have been
                    unsubscribed, and you will
                    no longer receive further SMS messages from us.
                </p>

                <h2 class="text-2xl font-bold mb-6">6. Help and Support for SMS Messaging Service</h2>
                <p class="mb-6">
                    For assistance with the SMS messaging service, reply <strong>HELP</strong> to any SMS message you
                    receive from us, text <strong>HELP</strong> to {{ PhoneHelper::format($companyData->phone) }}, or visit
                    our <a href="{{ route('privacy-policy') }}" target="_blank"
                        class="text-yellow-600 hover:text-yellow-700 underline">Privacy Policy</a> and <a
                        href="{{ route('terms-and-conditions') }}" target="_blank"
                        class="text-yellow-600 hover:text-yellow-700 underline">Terms of Service</a>.
                </p>

                <h2 class="text-2xl font-bold mb-6">Links to Other Websites</h2>
                <p class="mb-6">
                    Our Service may contain links to third-party web sites or services that are not owned or controlled by
                    the Company.
                    The Company has no control over, and assumes no responsibility for, the content, privacy policies, or
                    practices of any
                    third party web sites or services.
                </p>

                <h2 class="text-2xl font-bold mb-6">Termination</h2>
                <p class="mb-6">
                    We may terminate or suspend Your access immediately, without prior notice or liability, for any reason
                    whatsoever,
                    including without limitation if You breach these Terms and Conditions. Upon termination, Your right to
                    use the Service
                    will cease immediately.
                </p>

                <h2 class="text-2xl font-bold mb-6">Limitation of Liability</h2>
                <p class="mb-6">
                    Notwithstanding any damages that You might incur, the entire liability of the Company and any of its
                    suppliers under
                    any provision of this Terms and Your exclusive remedy for all of the foregoing shall be limited to the
                    amount actually
                    paid by You through the Service or 100 USD if You haven't purchased anything through the Service.
                </p>

                <h2 class="text-2xl font-bold mb-6">"AS IS" and "AS AVAILABLE" Disclaimer</h2>
                <p class="mb-6">
                    The Service is provided to You "AS IS" and "AS AVAILABLE" and with all faults and defects without
                    warranty of any kind.
                </p>

                <h2 class="text-2xl font-bold mb-6">7. Privacy Policy and Additional Terms of Service</h2>
                <p class="mb-6">
                    Your privacy is important to Us. For information about how we collect, use, and protect your personal
                    information,
                    including data collected through the Service, please see our Privacy Policy.
                </p>

                <h2 class="text-2xl font-bold mb-6">8. Changes to These Terms and Conditions</h2>
                <p class="mb-6">
                    We reserve the right, at Our sole discretion, to modify or replace these Terms at any time. If a
                    revision is material
                    We will make reasonable efforts to provide at least 30 days notice prior to any new terms taking effect.
                </p>

                <h2 class="text-2xl font-bold mb-6">9. Contact Us</h2>
                <p class="mb-6">
                    If you have any questions about these Terms and Conditions or the Service, You can contact us:
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>By email: <a href="mailto:info@vgeneralcontractors.com"
                            class="text-yellow-600 hover:text-yellow-700">
                            info@vgeneralcontractors.com</a></li>
                    <li>By visiting the "Contact" section of our website:
                        <a href="https://vgeneralcontractors.com/" class="text-yellow-600 hover:text-yellow-700">
                            https://vgeneralcontractors.com/</a>
                    </li>
                </ul>

                <div class="text-sm text-gray-600 mt-12 pt-6 border-t">
                    <p>Last updated: January 24, 2025</p>
                    <p>Version: 1.1</p>
                </div>
            </div>
        </div>
    </section>
@endsection
