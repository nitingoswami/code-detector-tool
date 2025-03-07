<x-app-layout>
    <x-slot name="header">
        <div class="d-flex w-100 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Controller') }}
            </h2>
            <div>
                <button type="button" id="add-more" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create User
                </button>
            </div>
        </div>
    </x-slot>
    
    <div class="container mx-auto mt-6">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">S No.</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Team Code</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Slack Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date-Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">User Role</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody id="file-table-body">
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div> 

    <!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-md w-1/3">
        <div class="flex items-center justify-between w-full">
            <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Edit User</h3>
            <button type="button" id="closeEditModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm">
            <input type="hidden" id="editUserId">
            <div class="mb-4">
                <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="editName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="editTeamCode" class="block text-sm font-medium text-gray-700">Team Code</label>
                <input type="text" id="editTeamCode" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="editSlackName" class="block text-sm font-medium text-gray-700">Slack Name</label>
                <input type="text" id="editSlackName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editEmail" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="editUserRole" class="block text-sm font-medium text-gray-700">User Role</label>
                <input type="text" id="editUserRole" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="flex justify-center">
                <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>
   

   <!-- Create User Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center {{ $errors->any() ? '' : 'hidden' }}">
    <div class="bg-white p-6 rounded-md w-1/3">
        <div class="flex items-center justify-between w-full">
            <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Create New User</h3>
            <button type="button" id="closeCreateModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.user.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="email" value="{{ old('email') }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="slack_name" class="block text-sm font-medium text-gray-700">Slack Name</label>
                <input type="text" id="slack_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="slack_name" value="{{ old('slack_name') }}" required autocomplete="slack_name">
                <x-input-error :messages="$errors->get('slack_name')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="team_code" class="block text-sm font-medium text-gray-700">Team Code</label>
                <input type="text" id="team_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="team_code" value="{{ old('team_code') }}" required autocomplete="team_code">
                <x-input-error :messages="$errors->get('team_code')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="password_confirmation" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex justify-center">
                <button type="button" id="closeCreateModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
            </div>
        </form>
    </div>
</div>


    <script>

     document.addEventListener('DOMContentLoaded', function () {
        // Check if there are validation errors
        if ("{{ $errors->any() }}" == "1") {
            document.getElementById('createModal').classList.remove('hidden');
        }
    });
        // Function to fetch and display users
        async function fetchUserData() {
            try {
                const response = await fetch('/user-data'); // Fetch data from the user-data endpoint
                
                // Check if the response is successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json(); // Parse the JSON response
                if (data.success) {
                    const users = data.users; // Access the users array from the response

                    const tableBody = document.getElementById('file-table-body');
                    tableBody.innerHTML = ''; // Clear the loading message

                    // Loop through each user and add a row to the table
                    users.forEach((user, index) => {
                        let date = new Date(user.created_at);
                        let formattedDateTime = date.toLocaleDateString('en-IN', { month: 'short', day: '2-digit', year: 'numeric' }).replace(',', '').replace(' ', '/') + 
                            ' ' + date.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit', hour12: true });

                        const row = document.createElement('tr');
                        row.classList.add('bg-white'); 
                        row.innerHTML = `
                            <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                            <td class="border border-gray-300 px-4 py-2">${user.name}</td>
                            <td class="border border-gray-300 px-4 py-2">${user.team_code}</td>
                            <td class="border border-gray-300 px-4 py-2">${user.slack_name}</td>
                            <td class="border border-gray-300 px-4 py-2">${user.email}</td>
                            <td class="border border-gray-300 px-4 py-2">${formattedDateTime}</td>
                            <td class="border border-gray-300 px-4 py-2">${user.user_role}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button data-id="${user.id}" data-name="${user.name}" data-team_code="${user.team_code}" data-slack_name="${user.slack_name}" data-email="${user.email}" data-user_role="${user.user_role}" class="edit-btn py-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button data-id="${user.id}" class="delete-btn px-2 py-1"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    // If success is false, handle error from backend response
                    console.error('Error:', data.error);
                    const tableBody = document.getElementById('file-table-body');
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data: ' + data.error + '</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching user data:', error);
                const tableBody = document.getElementById('file-table-body');
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data</td></tr>';
            }
        }

        // Fetch user data when the page loads
        document.addEventListener('DOMContentLoaded', fetchUserData);

        document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data on page load
    fetchUserData();

    // Handle the click event for edit and delete buttons
    document.getElementById('file-table-body').addEventListener('click', function (event) {
        const editButton = event.target.closest('.edit-btn'); // Ensure we get the button
        if (editButton) {
            const userId = editButton.getAttribute('data-id');
            const userName = editButton.getAttribute('data-name');
            const userTeamCode = editButton.getAttribute('data-team_code');
            const userSlackName = editButton.getAttribute('data-slack_name');
            const userEmail = editButton.getAttribute('data-email');
            const userRole = editButton.getAttribute('data-user_role');

            // Populate the modal form with user data
            document.getElementById('editUserId').value = userId;
            document.getElementById('editName').value = userName;
            document.getElementById('editTeamCode').value = userTeamCode;
            document.getElementById('editSlackName').value = userSlackName;
            document.getElementById('editEmail').value = userEmail;
            document.getElementById('editUserRole').value = userRole;

            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
            return;
        }

        const deleteButton = event.target.closest('.delete-btn'); // Ensure we get the button
        if (deleteButton) {
            const userId = deleteButton.getAttribute('data-id');
            deleteUser(userId);
        }
    });


    async function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user?')) {
            return;
        }

        try {
            const response = await fetch(`/user-delete/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('User deleted successfully!');
                fetchUserData(); // Refresh user list
            } else {
                alert('Failed to delete user.');
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            alert('An error occurred while deleting the user.');
        }
    }

    // Close the modal
    document.getElementById('closeEditModal').addEventListener('click', function () {
        document.getElementById('editModal').classList.add('hidden');
    });

    // Handle form submission
    document.getElementById('editForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const userId = document.getElementById('editUserId').value;
        const name = document.getElementById('editName').value;
        const teamCode = document.getElementById('editTeamCode').value;
        const slackName = document.getElementById('editSlackName').value;
        const email = document.getElementById('editEmail').value;
        const userRole = document.getElementById('editUserRole').value;

        const updatedUser = { name, team_code: teamCode, slack_name: slackName, email, user_role: userRole };

        try {
            const response = await fetch(`/user-update/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(updatedUser),
            });

            const data = await response.json();

            if (data.success) {
                alert('User updated successfully!');
                fetchUserData(); // Refresh the user list
                document.getElementById('editModal').classList.add('hidden'); // Close the modal
            } else {
                alert('Email Already Exist');
            }
        } catch (error) {
            console.error('Error updating user:', error);
            alert('An error occurred while updating the user');
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data on page load
    fetchUserData();

    // Handle the click event for edit, delete, and create buttons
    document.getElementById('file-table-body').addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-btn')) {
            // Edit user logic...
        } else if (event.target.classList.contains('delete-btn')) {
            // Delete user logic...
        }
    });

    // Handle the click event for Create User button
    document.getElementById('add-more').addEventListener('click', function () {
        // Show the Create User modal
        document.getElementById('createModal').classList.remove('hidden');
    });

    // Close Create User Modal
   document.querySelectorAll('#closeCreateModal').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('createModal').classList.add('hidden');
        });
   });

    // Close Edit User Modal
   document.querySelectorAll('#closeEditModal').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('editModal').classList.add('hidden');
        });
    });
  
});


    </script>

</x-app-layout>
