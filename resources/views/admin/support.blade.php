@extends('layouts.contentNavbarLayout')

@section('title', 'Customer Support & Help')

@section('content')
  <section class="glass rounded-2xl p-6 shadow-md">

    <div class="mb-6">
      <h2 class="text-2xl font-bold text-slate-800 mb-2">Customer Support</h2>
      <p class="text-slate-600">Need help? Reach out to our support team. We're here to assist you 24/7.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Phone Support Card -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 p-6 flex flex-col items-center text-center">
        <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-phone fa-lg"></i>
        </div>
        <h3 class="font-semibold text-lg mb-2">Phone Support</h3>
        <p class="text-slate-600 mb-4">Call us anytime for instant assistance.</p>
        <a href="tel:+1234567890" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-full font-semibold transition">Call Now</a>
      </div>

      <!-- Email Support Card -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 p-6 flex flex-col items-center text-center">
        <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-envelope fa-lg"></i>
        </div>
        <h3 class="font-semibold text-lg mb-2">Email Support</h3>
        <p class="text-slate-600 mb-4">Send us an email and we will respond promptly.</p>
        <a href="mailto:support@example.com" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-full font-semibold transition">Send Email</a>
      </div>

      <!-- Live Chat Card -->
      <div class="bg-white rounded-2xl shadow hover:shadow-lg transition duration-300 p-6 flex flex-col items-center text-center">
        <div class="bg-purple-100 text-purple-600 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-comments fa-lg"></i>
        </div>
        <h3 class="font-semibold text-lg mb-2">Live Chat</h3>
        <p class="text-slate-600 mb-4">Chat with our support team in real-time for quick solutions.</p>
        <a href="" class="bg-purple-500 hover:bg-purple-60 px-4 py-2 rounded-full font-semibold transition">Start Chat</a>
      </div>
    </div>

    <!-- FAQ Section -->
{{--    <div class="mt-10">--}}
{{--      <h2 class="text-xl font-bold mb-4 text-slate-800">Frequently Asked Questions</h2>--}}
{{--      <div class="space-y-3">--}}
{{--        <div class="bg-white rounded-xl shadow p-4 hover:shadow-md transition">--}}
{{--          <h4 class="font-semibold text-slate-700">How do I reset my password?</h4>--}}
{{--          <p class="text-slate-600 mt-1">Click on 'Change Password' in your profile settings and follow the instructions.</p>--}}
{{--        </div>--}}
{{--        <div class="bg-white rounded-xl shadow p-4 hover:shadow-md transition">--}}
{{--          <h4 class="font-semibold text-slate-700">How can I view my attendance report?</h4>--}}
{{--          <p class="text-slate-600 mt-1">Navigate to the 'Employee Reports' section and filter by date or employee name.</p>--}}
{{--        </div>--}}
{{--        <div class="bg-white rounded-xl shadow p-4 hover:shadow-md transition">--}}
{{--          <h4 class="font-semibold text-slate-700">How do I contact support?</h4>--}}
{{--          <p class="text-slate-600 mt-1">You can call, email, or start a live chat using the cards above.</p>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
  </section>
@endsection

@push('scripts')
  <!-- Font Awesome for Icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
