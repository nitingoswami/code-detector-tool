<x-app-layout>
    <x-slot name="header">
        <div class="d-flex w-100 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Projects list') }}
            </h2>
            <div class="flex space-x-3">
                <div>
                    <button type="button" id="add-project-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Project
                    </button>
                </div>

            </div>
        </div>
    </x-slot>

    <div class="container mx-auto mt-6">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">S No.</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Project Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date-Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody id="file-table-body-project">
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>


    <!-- Edit Project Modal -->
    <div id="editProjectModal" class="fixed inset-1  bg-gray-500 bg-opacity-50 items-center justify-center hidden flex">
        <div class="bg-white p-6 rounded-md w-1/3">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Edit Project</h3>
                <button type="button" id="closeEditProjectModal" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editProjectForm">
                <input type="hidden" id="editProjectId">
                <div class="mb-4">
                    <label for="editProjectName" class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" id="editProjectName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="flex justify-center">
                    <button type="button" id="closeEditProjectModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Create Project Modal -->
    <div id="createProjectModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center {{ $errors->any() ? '' : 'hidden' }}">
        <div class="bg-white p-6 rounded-md w-1/3">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Create New Project</h3>
                <button type="button" id="closeCreateProjectModal" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="mainForm" method="POST" action="{{ route('admin.createProject.store') }}">
                <!-- "{{ route('admin.createProject.store') }}" -->
                @csrf
                <div class="mb-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="project_name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="flex justify-center">
                    <button type="button" id="closeCreateProjectModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                    <button id="projectSubmitBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('projectSubmitBtn').addEventListener('click', function() {
            formSubmitHandler();
        });


        const formSubmitHandler = async () => {
            const form = document.getElementById('mainForm');
            const formData = new FormData(form);

            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const responseData = await response.json();

                if (response.ok) {
                    alert('Project created successfully!');
                    createModal = document.getElementById('createProjectModal');
                    createModal.classList.add('hidden');
                    fetchProjectData();
                } else {
                    alert('There was an error creating the project');
                }

            } catch (error) {
                console.log(`Error : ${error}`);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are validation errors
            if ("{{ $errors->any() }}" == "1") {
                document.getElementById('createProjectModal').classList.remove('hidden');
            }
        });


        async function fetchProjectData() {
            try {
                const response = await fetch('/project-data'); // Fetch data from the project-data endpoint

                // Check if the response is successful
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json(); // Parse the JSON response
                if (data.success) {
                    const projects = data.projects; // Access the projects array from the response

                    const tableBody = document.getElementById('file-table-body-project');
                    tableBody.innerHTML = ''; // Clear the loading message

                    // Loop through each project and add a row to the table
                    projects.forEach((project, index) => {
                        let date = new Date(project.created_at);
                        let formattedDateTime = date.toLocaleDateString('en-IN', {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric'
                            }).replace(',', '').replace(' ', '/') +
                            ' ' + date.toLocaleTimeString('en-IN', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });

                        const row = document.createElement('tr');
                        row.classList.add('bg-white');
                        row.innerHTML = `
                            <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                            <td class="border border-gray-300 px-4 py-2">${project.project_name}</td>
                          
                            <td class="border border-gray-300 px-4 py-2">${formattedDateTime}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button data-id="${project.id}" data-name="${project.project_name}"  class="edit-btn py-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button data-id="${project.id}" class="delete-btn px-2 py-1"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    // If success is false, handle error from backend response
                    console.error('Error:', data.error);
                    const tableBody = document.getElementById('file-table-body-project');
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data: ' + data.error + '</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching project data:', error);
                const tableBody = document.getElementById('file-table-body-project');
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data</td></tr>';
            }
        }


        // Fetch project data when the page loads
        document.addEventListener('DOMContentLoaded', fetchProjectData);

        document.addEventListener('DOMContentLoaded', function() {
            // Handle the click event for edit and delete buttons
            document.getElementById('file-table-body-project').addEventListener('click', function(event) {
                const editButton = event.target.closest('.edit-btn'); // Ensure we get the button
                if (editButton) {
                    const projectId = editButton.getAttribute('data-id');
                    const projectName = editButton.getAttribute('data-name');

                    // Populate the modal form with project data
                    document.getElementById('editProjectId').value = projectId;
                    document.getElementById('editProjectName').value = projectName;

                    // Show the modal
                    document.getElementById('editProjectModal').classList.remove('hidden');
                    return;
                }

                const deleteButton = event.target.closest('.delete-btn');
                if (deleteButton) {
                    const projectId = deleteButton.getAttribute('data-id');
                    deleteProject(projectId);
                }
            });


            async function deleteProject(projectId) {
                if (!confirm('Are you sure you want to delete this project?')) {
                    return;
                }

                try {
                    const response = await fetch(`/project-delete/${projectId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('project deleted successfully!');
                        fetchProjectData();//Refresh Project list after successful project fetching.
                        
                    }
                } catch (error) {
                    console.error('Error deleting project:', error);
                    alert('An error occurred while deleting the project.');
                }
            }

            // Close the Edit project modal
            document.getElementById('closeEditProjectModal').addEventListener('click', function() {
                document.getElementById('editProjectModal').classList.add('hidden');
            });

            // Handle form submission
            document.getElementById('editProjectForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const projectId = document.getElementById('editProjectId').value;
                const project_name = document.getElementById('editProjectName').value;

                const updatedProject = {
                    project_name,
                };

                try {
                    const response = await fetch(`/project-update/${projectId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(updatedProject),
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Project updated successfully!');
                        fetchProjectData(); // Refresh the project list
                        document.getElementById('editProjectModal').classList.add('hidden'); // Close the modal
                    } else {
                        alert('Email Already Exist');
                    }
                } catch (error) {
                    console.error('Error updating project:', error);
                    alert('An error occurred while updating the project');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            fetchProjectData();


            // Handle the click event for edit, delete, and create buttons
            document.getElementById('file-table-body-project').addEventListener('click', function(event) {
                if (event.target.classList.contains('edit-btn')) {
                    // Edit project logic...
                } else if (event.target.classList.contains('delete-btn')) {
                    // Delete project logic...
                }
            });


            // Handle the click event for Create Project button
            document.getElementById('add-project-btn').addEventListener('click', function() {
                // Show the Create Project modal
                document.getElementById('createProjectModal').classList.remove('hidden');
                document.getElementById('name').value = '';
            });


            // Close Create Project Modal
            document.querySelectorAll('#closeCreateProjectModal').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('createProjectModal').classList.add('hidden');
                });
            });


            // Close Edit Project Modal
            document.querySelectorAll('#closeEditProjectModal').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('editProjectModal').classList.add('hidden');
                });
            });
        });
    </script>

</x-app-layout>