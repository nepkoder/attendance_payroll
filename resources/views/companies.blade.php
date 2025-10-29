<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto py-10">

  <!-- Header -->
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Company Management</h1>

  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
@endif

<!-- Add New Company Form -->
  <div class="bg-white shadow rounded p-6 mb-8 max-w-3xl mx-auto">
    <h2 class="text-xl font-semibold mb-6">Add New Company</h2>

    <form action="{{ route('companies.store') }}" method="POST" class="space-y-5">
    @csrf

    <!-- Company Details -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 mb-1">Company Name</label>
          <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Subdomain</label>
          <input type="text" name="subdomain" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Database Name</label>
          <input type="text" name="db_name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Database Username</label>
          <input type="text" name="db_username" value="root" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Database Password</label>
          <input type="password" name="db_password" value="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Database Host</label>
          <input type="text" name="db_host" value="127.0.0.1" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
          <label class="block text-gray-700 mb-1">Database Port</label>
          <input type="number" name="db_port" value="3306" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
      </div>

      <!-- Default Admin User Details -->
      <div class="mt-6">
        <h3 class="text-lg font-medium mb-3">Default Tenant Admin</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 mb-1">Admin Name</label>
            <input type="text" name="admin_name" value="Admin User" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Admin Email</label>
            <input type="email" name="admin_email" placeholder="admin@tenant.com" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Admin Password</label>
            <input type="password" name="admin_password" value="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
          </div>
        </div>
      </div>

      <button type="submit" class="mt-6 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-medium">Create Company</button>
    </form>
  </div>

  <!-- List of Companies -->
  <div class="bg-white shadow rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Company List</h2>
    <table class="w-full text-left border-collapse">
      <thead>
      <tr class="bg-gray-100">
        <th class="border px-4 py-2">ID</th>
        <th class="border px-4 py-2">Name</th>
        <th class="border px-4 py-2">Subdomain</th>
        <th class="border px-4 py-2">Database</th>
        <th class="border px-4 py-2">Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach($companies as $company)
        <tr>
          <td class="border px-4 py-2">{{ $company->id }}</td>
          <td class="border px-4 py-2">{{ $company->name }}</td>
          <td class="border px-4 py-2">{{ $company->subdomain }}</td>
          <td class="border px-4 py-2">{{ $company->db_name }}</td>
          <td class="border px-4 py-2 space-x-2">
            <!-- Edit -->
            <a href="{{ route('companies.edit', $company->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>

            <!-- Delete -->
            <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
