<!-- Contact Form Section -->
<div class="bg-white py-16 fade-in-section">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Get Your Free Inspection</h2>
                <p class="text-lg text-gray-600">Fill out the form below and our team will contact you shortly to
                    schedule your free inspection.</p>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" id="phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    </div>

                    <!-- City Field -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    </div>

                    <!-- Zip Code Field -->
                    <div>
                        <label for="zipcode" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                        <input type="text" name="zipcode" id="zipcode" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    </div>

                    <!-- Insurance Property Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Property</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="insurance" value="yes"
                                    class="form-radio text-yellow-500 h-5 w-5">
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="insurance" value="no"
                                    class="form-radio text-yellow-500 h-5 w-5">
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Message Field -->
                    <div class="md:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Comment or
                            Message</label>
                        <textarea name="message" id="message" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition resize-none"></textarea>
                    </div>
                </div>

                <!-- SMS Consent Checkbox -->
                <div class="mt-6">
                    <label class="inline-flex items-start">
                        <input type="checkbox" name="sms_consent" class="form-checkbox text-yellow-500 mt-1">
                        <span class="ml-2 text-sm text-gray-600">
                            Yes, I would like to receive text messages from <span class="font-bold">V GENERAL
                                CONTRACTORS</span> with offers, appointment reminders, and updates on roofing services.
                            <span class="font-bold">Messaging Frequency may vary</span>. I understand that I can cancel
                            my subscription at any time by replying <span class="font-bold">STOP. Reply HELP for
                                assistance</span>. Message and data rates apply. <a href="#"
                                class="text-yellow-500 hover:text-yellow-600">Privacy Policy</a> and <a href="#"
                                class="text-yellow-500 hover:text-yellow-600">Terms of Service</a>.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <button type="submit"
                        class="bg-yellow-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-yellow-600 transform hover:scale-105 transition duration-200 ease-in-out shadow-lg">
                        Schedule Free Inspection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
