@extends('layouts.main')

@section('title', 'Privacy Policy - V General Contractors')
@section('meta_description',
    'Read our privacy policy to understand how V General Contractors handles and protects your
    personal information. Learn about your data rights and our security measures.')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 bg-gray-900 text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-black opacity-60"></div>
        </div>
        <div class="relative container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
            <p class="text-xl text-gray-300">Last updated: 01-23-2025 | Version 1.1</p>
        </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-yellow-500">Home</a>
                <span>/</span>
                <span class="text-yellow-500">Privacy Policy</span>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="prose max-w-none">
                <h2 class="text-2xl font-bold mb-6">1. General Information</h2>

                <h3 class="text-xl font-semibold mb-4">1.1 Identification of the Data Controller</h3>
                <p class="mb-6">
                    VG General Contractors - with fiscal address at 1302 Waugh Dr., No. 810, Houston, Texas 77019 is
                    responsible
                    for the processing of the personal data you provide to us.
                </p>

                <h3 class="text-xl font-semibold mb-4">1.2 Privacy Contact</h3>
                <p class="mb-6">
                    You can contact our Data Protection Officer at:<br>
                    Email: <a href="mailto:info@vgeneralcontractors.com"
                        class="text-yellow-600 hover:text-yellow-700">info@vgeneralcontractors.com</a><br>
                    Phone: <a href="tel:+13466920757" class="text-yellow-600 hover:text-yellow-700">(346) 692-0757</a>
                </p>

                <h2 class="text-2xl font-bold mb-6">2. Data Collected</h2>

                <h3 class="text-xl font-semibold mb-4">2.1 Types of Data</h3>
                <p class="mb-4">The Company may collect the following personal data:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Full name of the property owner</li>
                    <li>Property address</li>
                    <li>Phone number</li>
                    <li>Email address</li>
                    <li>Information about the roof structure</li>
                    <li>Property insurance details</li>
                    <li>Photographs of the property for inspection</li>
                </ul>

                <h3 class="text-xl font-semibold mb-4">2.2 Collection Methods</h3>
                <p class="mb-4">Data may be collected through:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Roof inspection forms</li>
                    <li>Online quote requests</li>
                    <li>On-site technical visits</li>
                    <li>Telephone communications</li>
                    <li>Emails</li>
                    <li>Web contact form</li>
                </ul>

                <h3 class="text-xl font-semibold mb-4">2.3 SMS Opt-in Data (Text Messages)</h3>
                <p class="mb-6">
                    If you choose to consent (opt-in) to receive text messages (SMS) from us for marketing, promotions, or
                    service
                    updates, we will collect and store your consent and your phone number associated with that consent (SMS
                    opt-in data).<br><br>
                    We want to explicitly assure you that we will NOT share this SMS opt-in and consent data with any third
                    parties
                    for their own marketing purposes or for any purpose other than strictly necessary to send you the text
                    messages
                    you have requested and consented to.<br><br>
                    We may use SMS sending service providers to facilitate the sending of these messages, but these
                    providers are
                    contractually obligated to protect the confidentiality of this data and to use it only to send you
                    messages on
                    our behalf, in accordance with your consent.
                </p>

                <h2 class="text-2xl font-bold mb-6">3. Purposes of Processing</h2>

                <h3 class="text-xl font-semibold mb-4">3.1 Main Objectives</h3>
                <p class="mb-4">Your data will be used for:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Scheduling roof inspections</li>
                    <li>Preparing repair or replacement quotes</li>
                    <li>Managing the repair or installation process</li>
                    <li>Warranty follow-up</li>
                    <li>Communications related to roofing services</li>
                    <li>Administrative and accounting management</li>
                    <li>Sending text messages (SMS) to those customers who have given their consent (opt-in)</li>
                </ul>

                <h3 class="text-xl font-semibold mb-4">3.2 Legal Basis</h3>
                <p class="mb-4">Data processing is carried out under the following legal bases:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Consent of the data subject (especially for sending marketing SMS)</li>
                    <li>Performance of a service contract</li>
                    <li>Compliance with legal obligations</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">4. Rights of the Data Subject</h2>

                <h3 class="text-xl font-semibold mb-4">4.1 ARCO Rights</h3>
                <p class="mb-4">You have the right to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Access your personal data</li>
                    <li>Rectify inaccurate information</li>
                    <li>Cancel your data</li>
                    <li>Object to processing</li>
                    <li>Limit the use of your data</li>
                </ul>

                <h3 class="text-xl font-semibold mb-4">4.2 Exercising Rights</h3>
                <p class="mb-4">To exercise your rights, you must:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Send a written request</li>
                    <li>Attach documentation that proves your identity</li>
                    <li>Describe in a precise manner the right you wish to exercise</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">5. Security Measures</h2>

                <h3 class="text-xl font-semibold mb-4">5.1 Data Protection</h3>
                <p class="mb-4">We implement technical and organizational measures to protect your data:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Encryption of inspection information</li>
                    <li>Restricted access to customer data</li>
                    <li>Secure backups of documentation</li>
                    <li>Confidentiality protocols for technical personnel</li>
                    <li>Secure management of photographs and roof reports</li>
                    <li>Specific security measures to protect SMS opt-in data</li>
                </ul>

                <h3 class="text-xl font-semibold mb-4">5.2 Data Transfers</h3>
                <p class="mb-6">
                    We do not sell or transfer personal data to third parties without your express consent, except in the
                    following cases:
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Insurance companies (with customer authorization)</li>
                    <li>Construction material suppliers (strictly for service execution)</li>
                    <li>Competent authorities (by legal requirement)</li>
                    <li>SMS sending service providers (for consented text messages)</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">6. Cookies and Tracking Technologies</h2>

                <h3 class="text-xl font-semibold mb-4">6.1 Use of Cookies</h3>
                <p class="mb-4">Our website uses cookies to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Improve the browsing experience</li>
                    <li>Analyze site traffic</li>
                    <li>Personalize quote content</li>
                    <li>Manage contact forms</li>
                </ul>
                <p class="mb-6">You can configure your browser to reject cookies.</p>

                <h2 class="text-2xl font-bold mb-6">7. Data Retention</h2>
                <p class="mb-4">We will retain your data:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Contact data: 5 years after the last service</li>
                    <li>Inspection reports: 10 years</li>
                    <li>Billing data: according to accounting requirements</li>
                    <li>SMS opt-in data: as long as consent is valid</li>
                </ul>

                <h2 class="text-2xl font-bold mb-6">8. Modifications to the Policy</h2>
                <p class="mb-6">
                    We reserve the right to modify this policy. We will notify you of significant changes.
                </p>

                <h2 class="text-2xl font-bold mb-6">9. Consent</h2>
                <p class="mb-6">
                    By requesting our services or using our website, you accept the terms of this privacy policy.
                    Specifically,
                    by providing your phone number and giving your consent to receive SMS messages, you accept the
                    processing of
                    your data for this purpose, as described in this policy.
                </p>

                <h2 class="text-2xl font-bold mb-6">10. Applicable Legislation</h2>
                <p class="mb-6">
                    This policy is governed by the laws of Houston, Texas USA, with special reference to the regulations on
                    personal data protection and construction.
                </p>

                <div class="text-sm text-gray-600 mt-12 pt-6 border-t">
                    <p>Last updated: 01-23-2025</p>
                    <p>Version: 1.1</p>
                </div>
            </div>
        </div>
    </section>
@endsection
