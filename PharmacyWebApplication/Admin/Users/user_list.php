<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
<link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Lexend%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<title>Stitch Design</title>
<link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style type="text/tailwindcss">
      :root {
        --primary-color: #ec1313;
        --secondary-color: #fef2f2;
        --background-color: #ffffff;
        --text-primary: #1a1a1a;
        --text-secondary: #4a4a4a;
        --accent-color: #fca5a5;
      }
      body {
        font-family: "Lexend", sans-serif;
        background-color: var(--background-color);
        color: var(--text-primary);
      }
    </style>
</head>
<body class="bg-background-color text-primary">
<div class="relative flex size-full min-h-screen flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 px-10 py-3">
<div class="flex items-center gap-4 text-primary">
<div class="size-6 text-primary-color">
<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="M6 6H42L36 24L42 42H6L12 24L6 6Z" fill="currentColor"></path></svg>
</div>
<h2 class="text-primary text-xl font-bold leading-tight tracking-[-0.015em]">MediCo</h2>
</div>
<div class="flex flex-1 items-center justify-end gap-6">
<nav class="flex items-center gap-8">
<a class="text-secondary hover:text-primary-color text-sm font-medium leading-normal transition-colors" href="#">Dashboard</a>
<a class="text-secondary hover:text-primary-color text-sm font-medium leading-normal transition-colors" href="#">Orders</a>
<a class="text-secondary hover:text-primary-color text-sm font-medium leading-normal transition-colors" href="#">Inventory</a>
<a class="text-primary text-sm font-bold leading-normal" href="#">Users</a>
<a class="text-secondary hover:text-primary-color text-sm font-medium leading-normal transition-colors" href="#">Reports</a>
</nav>
<div class="flex items-center gap-4">
<button class="flex size-10 cursor-pointer items-center justify-center overflow-hidden rounded-full bg-secondary-color text-secondary hover:bg-accent-color hover:text-primary-color transition-colors">
<span class="material-symbols-outlined"> notifications </span>
</button>
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC9BHkR-0YFYhkKmiWuSoudiSYYZr0pG46Z9lN6kxgiYeBDSiyQSc96p9yXZH7Y4NzQUTyRmHtoOdhgq5AtYObDGq-lqz1TNNJMCwEN31ihnF7saUO-Zq4_E0gUTB36RTpeWqGNOjk6Xke7qHvS6A3WfYhPiA_nDZJ3S94lrOy5kh4MVloCpgIe9pwevqglpVt1Cc2vbYIm_HY9dmETfqZ1nJKJxig0ublBV1a8UT-86Har2Df9RlL555l-bsHOympIo0cM-mSV1A");'></div>
</div>
</div>
</header>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
<div class="bg-white rounded-md shadow-md p-6 border border-gray-200">
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
<h1 class="text-4xl font-extrabold text-primary">Users</h1>
<button class="bg-primary-color text-white px-6 py-3 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-primary-color focus:ring-opacity-50 transition duration-300 ease-in-out flex items-center gap-2">
<span class="material-symbols-outlined"> add </span>
<span class="truncate">Add User</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
<div class="md:col-span-2">
<label class="relative block">
<span class="absolute inset-y-0 left-0 flex items-center pl-3">
<span class="material-symbols-outlined text-gray-400"> search </span>
</span>
<input class="block w-full px-10 py-3 border border-gray-300 rounded-md bg-white text-primary placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-color focus:border-primary-color transition duration-200 ease-in-out" placeholder="Search users by name..." type="text"/>
</label>
</div>
<div class="flex gap-4">
<div class="relative w-full">
<select class="block w-full px-4 py-3 border border-gray-300 rounded-md bg-white text-primary placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-color focus:border-primary-color transition duration-200 ease-in-out appearance-none">
<option disabled="" selected="">Role</option>
<option>Pharmacist</option>
<option>Patient</option>
<option>Supplier</option>
</select>
<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
<span class="material-symbols-outlined"> expand_more </span>
</div>
</div>
<div class="relative w-full">
<select class="block w-full px-4 py-3 border border-gray-300 rounded-md bg-white text-primary placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-color focus:border-primary-color transition duration-200 ease-in-out appearance-none">
<option disabled="" selected="">Status</option>
<option>Active</option>
<option>Inactive</option>
</select>
<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
<span class="material-symbols-outlined"> expand_more </span>
</div>
</div>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="bg-gray-50">
<th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Role</th>
<th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Status</th>
<th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Dr. Emily Carter</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Pharmacist</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Mr. David Lee</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Patient</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Ms. Sarah Jones</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Supplier</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"> Inactive </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Dr. Michael Brown</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Pharmacist</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Mrs. Jessica Wilson</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Patient</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Mr. Robert Green</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Supplier</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Dr. Olivia Taylor</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Pharmacist</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"> Inactive </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">Ms. Sophia Clark</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">Patient</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"> Active </span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<a class="text-primary-color hover:underline" href="#">View</a>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</main>
</div>
</div>

</body></html>