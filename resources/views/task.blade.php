<x-app-layout>
    <x-slot name="header">
        <div class="d-flex w-100 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Controller') }}
            </h2>
            <div>
                <button type="button" id="add-more" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Assign Task
                </button>
            </div>
        </div>
    </x-slot>
       
    <div class="container mx-auto mt-6">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">S No.</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">User name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Project Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Task Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
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

    <!-- Create Task Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center {{ $errors->any() ? '' : 'hidden' }}">
        <div class="bg-white p-6 rounded-md w-1/3">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Create Task</h3>
                <button type="button" id="closeCreateModal" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
          <form id="taskForm">
            @csrf
            <div class="mb-3">
                <label for="userSelect" class="block text-sm font-medium text-gray-700">Select User</label>
                <select id="userSelect" name="user_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="">Select a user</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Project</label>
                <input type="text" id="project" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="project" required>
            </div>

            <div class="mb-3">
                <label for="task_name" class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" id="task_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    rows="4" required></textarea>
            </div>

            <div class="flex justify-center">
                <button type="button" id="closeCreateModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
            </div>
        </form>

        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-md w-1/3">
        <div class="flex items-center justify-between w-full">
            <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow">Edit Task</h3>
            <button type="button" id="closeEditModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editTaskForm">
            @csrf
            <input type="hidden" id="edit_task_id" name="task_id">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Select User</label>
                <select id="edit_userSelect" name="user_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="">Select a user</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Project</label>
                <input type="text" id="edit_project" name="project" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" id="edit_task_name" name="task_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="edit_description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" rows="4" required></textarea>
            </div>
            <div class="flex justify-center">
                <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>
    

    <!-- JavaScript -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const openModal = document.getElementById("add-more");
        const closeModal = document.getElementById("closeCreateModal");
        const modal = document.getElementById("createModal");
        const taskForm = document.getElementById("taskForm");
        const taskTableBody = document.getElementById("file-table-body");

        openModal.addEventListener("click", function() {
            modal.classList.remove("hidden");
        });

        closeModal.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

        function fetchUserData() {
            fetch('/user-data')
                .then(response => response.json())
                .then(data => {
                    console.log(data, 'data');
                    populateUserList(data.users || []);
                })
                .catch(error => console.error('Error fetching user data:', error));
        }

        function populateUserList(users) {
            const userSelect = document.getElementById("userSelect");
            userSelect.innerHTML = '<option value="">Select a user</option>';

            users.forEach(user => {
                const option = document.createElement("option");
                option.value = user.name;
                option.textContent = user.name;
                userSelect.appendChild(option);
            });
        }

        fetchUserData();

        // Handle form submission using AJAX
        taskForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(taskForm);
            console.log(formData,'formData')
            fetch("{{ route('admin.task.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log("Success:", data);
                modal.classList.add("hidden"); // Close modal on success
                fetchTaskData(); // Reload tasks
            })
            .catch(error => console.error("Error:", error));
        });

        function fetchTaskData() {
            fetch("{{ route('admin.getTask.store') }}")
                .then(response => response.json())
                .then(data => {
                    console.log(data, 'Fetched Tasks');
                    populateTaskTable(data.task);
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        function populateTaskTable(tasks) {
            taskTableBody.innerHTML = ""; // Clear the table

            if (tasks.length === 0) {
                taskTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No tasks found</td></tr>';
                return;
            }

            tasks.forEach((task, index) => {
                const row = document.createElement("tr");
                    row.classList.add('bg-white'); 

                row.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.user_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.project_id}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.task_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.description}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button data-id="${task.id}" data-user_name="${task.user_name}" data-project_id="${task.project_id}" data-task_name="${task.task_name}" data-description="${task.description}" class="edit-btn py-1"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button data-id="${task.id}" class="delete-btn px-2 py-1"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                taskTableBody.appendChild(row);
            });
        }

        fetchTaskData(); 
    });
    
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById("editModal");
        const closeEditModalBtns = document.querySelectorAll("#closeEditModal");
        const editTaskForm = document.getElementById("editTaskForm");
        const editUserSelect = document.getElementById("edit_userSelect");
        const taskTableBody = document.getElementById("file-table-body");

        function fetchUserDataForEditModal(selectedUser) {
            fetch('/user-data')
                .then(response => response.json())
                .then(data => {
                    editUserSelect.innerHTML = '<option value="">Select a user</option>';

                    data.users.forEach(user => {
                        const option = document.createElement("option");
                        option.value = user.name;
                        option.textContent = user.name;
                        
                        if (user.name === selectedUser) {
                            option.selected = true;
                        }

                        editUserSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching user data:', error));
        }

        document.getElementById("file-table-body").addEventListener("click", function(e) {
            if (e.target.closest(".edit-btn")) {
                const button = e.target.closest(".edit-btn");
                
                document.getElementById("edit_task_id").value = button.dataset.id;
                document.getElementById("edit_project").value = button.dataset.project_id;
                document.getElementById("edit_task_name").value = button.dataset.task_name;
                document.getElementById("edit_description").value = button.dataset.description;

                fetchUserDataForEditModal(button.dataset.user_name);

                editModal.classList.remove("hidden");
            }
        });

        closeEditModalBtns.forEach(btn => btn.addEventListener("click", function() {
            editModal.classList.add("hidden");
        }));

        editTaskForm.addEventListener("submit", function(event) {
            event.preventDefault();
            
            const formData = new FormData(editTaskForm);
            const taskId = document.getElementById("edit_task_id").value;
            
            fetch(`/admin/update-task/${taskId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log("Updated Successfully:", data);
                editModal.classList.add("hidden");

                fetchTaskData();
            })
            .catch(error => console.error("Error updating task:", error));
        });

        function fetchTaskData() {
            fetch("{{ route('admin.getTask.store') }}")
                .then(response => response.json())
                .then(data => {
                    console.log(data, 'Fetched Tasks');
                    populateTaskTable(data.task);
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        function populateTaskTable(tasks) {
            taskTableBody.innerHTML = "";

            if (tasks.length === 0) {
                taskTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No tasks found</td></tr>';
                return;
            }

            tasks.forEach((task, index) => {
                const row = document.createElement("tr");
                row.classList.add('bg-white'); 

                row.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.user_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.project_id}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.task_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.description}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button data-id="${task.id}" data-user_name="${task.user_name}" data-project_id="${task.project_id}" data-task_name="${task.task_name}" data-description="${task.description}" class="edit-btn py-1"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button data-id="${task.id}" class="delete-btn px-2 py-1"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                taskTableBody.appendChild(row);
            });
        }

        fetchTaskData(); 
    });

    document.addEventListener("click", function (event) {
          const taskTableBody = document.getElementById("file-table-body");
        if (event.target.closest(".delete-btn")) {
            const taskId = event.target.closest(".delete-btn").dataset.id;
            if (confirm("Are you sure you want to delete this task?")) {
                console.log(taskId,'taskId')
                fetch(`/delete-task/${taskId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchTaskData();
                })
                .catch(error => console.error("Error:", error));
            }
        }
          function fetchTaskData() {
            fetch("{{ route('admin.getTask.store') }}")
                .then(response => response.json())
                .then(data => {
                    console.log(data, 'Fetched Tasks');
                    populateTaskTable(data.task);
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        function populateTaskTable(tasks) {
            taskTableBody.innerHTML = ""; // Clear the table

            if (tasks.length === 0) {
                taskTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No tasks found</td></tr>';
                return;
            }

            tasks.forEach((task, index) => {
                const row = document.createElement("tr");
                    row.classList.add('bg-white'); 

                row.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.user_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.project_id}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.task_name}</td>
                    <td class="border border-gray-300 px-4 py-2">${task.description}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button data-id="${task.id}" data-user_name="${task.user_name}" data-project_id="${task.project_id}" data-task_name="${task.task_name}" data-description="${task.description}" class="edit-btn py-1"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button data-id="${task.id}" class="delete-btn px-2 py-1"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                taskTableBody.appendChild(row);
            });
        }

        fetchTaskData(); 
    });







    </script>

</x-app-layout>
