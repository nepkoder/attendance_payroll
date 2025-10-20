@extends('layouts.employee')

@section('content')
  {{-- ✅ PROFILE DISPLAY SECTION --}}
  <section id="page-documents" class="glass rounded-2xl p-6 shadow-md mb-6">
    <h2 class="text-lg font-semibold mb-3">My Profile</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-700">
      <div>
        <span class="font-semibold text-slate-600">Name:</span>
        <p class="mt-1">{{ $employee->name }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Username:</span>
        <p class="mt-1">{{ $employee->username }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Email:</span>
        <p class="mt-1">{{ $employee->email ?? '-' }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Phone:</span>
        <p class="mt-1">{{ $employee->phone ?? '-' }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Company:</span>
        <p class="mt-1">{{ $employee->company ?? '-' }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Department:</span>
        <p class="mt-1">{{ $employee->department ?? '-' }}</p>
      </div>

      <div class="sm:col-span-2">
        <span class="font-semibold text-slate-600">Address:</span>
        <p class="mt-1">{{ $employee->address ?? '-' }}</p>
      </div>

      <div class="sm:col-span-2">
        <span class="font-semibold text-slate-600">Remarks:</span>
        <p class="mt-1">{{ $employee->remarks ?? '-' }}</p>
      </div>

      <div>
        <span class="font-semibold text-slate-600">Profile Image:</span>
        @if($employee->image)
          <img src="{{ asset('storage/' . $employee->image) }}" class="h-24 w-24 mt-2 object-cover rounded border">
        @else
          <p class="mt-1 text-slate-500">No image uploaded</p>
        @endif
      </div>

      <div>
        <span class="font-semibold text-slate-600">Document Image:</span>
        @if($employee->document_image)
          <img src="{{ asset('storage/' . $employee->document_image) }}" class="h-24 w-24 mt-2 object-cover rounded border">
        @else
          <p class="mt-1 text-slate-500">No document uploaded</p>
        @endif
      </div>
    </div>
  </section>

  {{-- ✅ PASSWORD CHANGE SECTION --}}
  <section id="page-changePassword" class="glass rounded-2xl p-6 shadow-md">
    <h2 class="text-lg font-semibold mb-3">Change Password</h2>

    @if($errors->any())
      <div class="p-3 mb-4 bg-red-100 text-red-800 rounded">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('employee.profile.password') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @csrf

      <div>
        <label class="text-sm text-slate-600">Current Password</label>
        <input type="password" name="current_password"
               class="w-full mt-1 p-3 border border-slate-300 rounded-lg" required>
      </div>

      <div>
        <label class="text-sm text-slate-600">New Password</label>
        <input type="password" name="new_password"
               class="w-full mt-1 p-3 border border-slate-300 rounded-lg" required>
      </div>

      <div>
        <label class="text-sm text-slate-600">Confirm New Password</label>
        <input type="password" name="new_password_confirmation"
               class="w-full mt-1 p-3 border border-slate-300 rounded-lg" required>
      </div>

      <div class="sm:col-span-2 mt-3">
        <button type="submit"
                class="px-5 py-3 rounded-lg bg-green-500 hover:bg-green-600 text-white font-semibold">
          Change Password
        </button>
      </div>
    </form>
  </section>
@endsection
