<x-app-layout>
    <x-slot name="header">
        <div class="d-flex w-100 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daily Report List') }}
            </h2>

            <div>
                @if (Auth::user()->user_role!="admin")
                <button type="button" id="add-more" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create Daily Report
                </button>
                @endif

            </div>
        </div>
    </x-slot>
      <style>
        /* Scrollable user list styling */
        #user-list {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            margin-top: 8px;
            background-color: #fff;
        }

        #user-list li {
            padding: 8px;
            cursor: pointer;
            list-style-type: none;
        }

        #user-list li:hover {
            background-color: #f0f0f0;
        }

        #user-list li.selected {
            background-color: #f0f0f0;
        }
    </style>

    <div class="mx-24 mt-6 grid grid-cols-12 gap-4 mt-24">
        <div class="mb-4 flex space-x-4 col-span-3">
            <div>
                {{-- <label for="name" class="block text-sm font-medium text-white mt-2">Filter By Date:</label> --}}
                <input type="date" id="filter-date" class="px-3 py-2 border rounded-md hidden">
                {{-- <div class="h-50"></div> --}}
                @if (Auth::user()->user_role=="admin")
                <label for="filter-user" class="block text-lg font-bold text-white mt-4">Filter By User:</label>
                <ul id="user-list">
        
                </ul>
                @endif
            </div>
        </div>
        <div class='col-span-9'>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">S No.</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Title</th>
                        {{-- <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Description</th> --}}
                        <th class="border border-gray-300 px-4 py-2 text-left">Actual Time</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                        {{-- <th class="border border-gray-300 px-4 py-2 text-left">File Path</th> --}}
                        <th class="border border-gray-300 px-4 py-2 text-left">Date-Time</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="file-table-body">
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-4">Loading...</td>
                    </tr>
                </tbody>
            </table>
            <div id="pagination" class="flex justify-center space-x-2 mt-4">
            </div>
        </div>

    </div>

    <!-- Add/Edit Files Modal -->
    <div id="fileModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-md w-1/3 shadow-lg">
            <div class="flex items-center justify-between w-full">
            <h3 id="modal-title" class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow text-center">Modal Title</h3>
            <button type="button" id="close-file-modal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            </div>
            <form id="file-form" method="POST">
                <p id="name" class="text-lg mb-2"></p>
                @csrf

                <!-- Title Input -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter Title" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="title-error"></p>
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter Description" rows="4" required></textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="description-error"></p>
                    
                </div>

                <div class="mb-4">
                    <label for="actual_time" class="block text-sm font-medium text-gray-700">Actual Time On Task (Minutes)</label>
                    <input type="text" id="actual_time" name="actual_time"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter Title" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="actual_time-error"></p>
                    
                    
                </div>

                <!-- File Path Inputs -->
                <div class="mb-4">
                    <label for="file_path" class="block text-sm font-medium text-gray-700">File Path</label>
                    <div id="file-input-container">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="file_path[]" class="file-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="/path/to/file.txt" required>
                            <button type="button" class="add-input-btn text-2xl text-blue-500" onclick="addInput()">+</button>
                        </div>
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="file-error"></p>
                
                </div>

                <!-- Buttons -->
                <div class="flex justify-center mt-6 space-x-2">
                    <button type="button" id="close-file-modal" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Close</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#filter-date", {
                inline: true, // Keeps the calendar always open
                dateFormat: "Y-m-d", // Adjust date format as needed
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const fileTableBody = document.getElementById('file-table-body');
            const filterDateInput = document.getElementById('filter-date');
            const userList = document.getElementById('user-list');
            const paginationContainer = document.getElementById('pagination');
            let currentPage = 1;
            let allUsers = [];
            let selectedUserId = null;
            
           
            //  Fetch users for the list
            function fetchUserData() {
                fetch('/user-data')
                    .then(response => response.json())
                    .then(data => {
                        allUsers = data.users || [];
                        populateUserList(allUsers);
                    })
                    .catch(error => console.error('Error fetching user data:', error));
            }

            //  Populate user list
            function populateUserList(users) {
                if (!userList) return;
                userList.innerHTML = ''; // Clear the list before populating

                // Add "All Users" list item at the top
                const allUsersItem = document.createElement('li');
                allUsersItem.dataset.userId = null; // Use null to represent "All Users"
                allUsersItem.style.marginTop = '2px';
                allUsersItem.style.display = 'flex';
                allUsersItem.style.alignItems = 'center';
                allUsersItem.style.cursor = 'pointer';

                // Create an icon element for "All Users"
                const allUsersIcon = document.createElement('i');
                allUsersIcon.classList.add('fa-solid', 'fa-circle-user');
                allUsersIcon.style.marginRight = '22px';
                allUsersIcon.style.fontSize = '28px';

                // Create a <p> tag for the "All Users" text
                const allUsersText = document.createElement('p');
                allUsersText.textContent = 'All Users';
                allUsersText.style.margin = '0';

                // Add the icon and text to the "All Users" list item
                allUsersItem.appendChild(allUsersIcon);
                allUsersItem.appendChild(allUsersText);

                // Add click event listener for "All Users"
                allUsersItem.addEventListener('click', () => {
                    selectedUserId = null; // Reset selected user ID
                    Array.from(userList.children).forEach(li => li.classList.remove('selected')); // Deselect all
                    allUsersItem.classList.add('selected'); // Select "All Users"
                    fetchFileData(1); // Fetch data for all users
                });

                // Append "All Users" to the list
                userList.appendChild(allUsersItem);

                // Add individual users
                users.forEach(user => {
                    const userContainer = document.createElement('li');
                    userContainer.dataset.userId = user.id;
                    userContainer.style.marginTop = '2px';
                    userContainer.style.display = 'flex';
                    userContainer.style.alignItems = 'center';
                    userContainer.style.cursor = 'pointer';

                    // Create an icon element
                    const icon = document.createElement('i');
                    icon.classList.add('fa-solid', 'fa-circle-user');
                    icon.style.marginRight = '22px';
                    icon.style.fontSize = '28px';

                    // Create a <p> tag for the user name text
                    const userNameText = document.createElement('p');
                    userNameText.textContent = user.name;
                    userNameText.style.margin = '0';

                    // Add the icon and user name text to the list item
                    userContainer.appendChild(icon);
                    userContainer.appendChild(userNameText);

                    // Add click event listener for selection
                    userContainer.addEventListener('click', () => {
                        // Toggle selection
                        if (selectedUserId === user.id) {
                            selectedUserId = null;
                            userContainer.classList.remove('selected');
                        } else {
                            selectedUserId = user.id;
                            Array.from(userList.children).forEach(li => li.classList.remove('selected'));
                            userContainer.classList.add('selected');
                        }
                        fetchFileData(1); // Refresh data with new filter
                    });

                    // Append the user list item to the user list
                    userList.appendChild(userContainer);
                });
            }
                if (filterDateInput) {
                    const today = new Date().toISOString().split("T")[0]; // Format YYYY-MM-DD
                    filterDateInput.value = today;
                }

            
            window.fetchFileData = function fetchFileData(page = 1) {
                currentPage = page;
                let url = `/all-data?page=${page}`;
                // Get selected filters
                const selectedDate = filterDateInput?.value;
                const selectedUser = selectedUserId;

                // Append filters to URL if selected
                const params = new URLSearchParams();
                if (selectedDate) params.append("date", selectedDate);
                if (selectedUser) params.append("user_id", selectedUser);

                if (params.toString()) url += `&${params.toString()}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        renderTable(data?.reports?.data || []);
                        renderPagination(data?.reports);
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        fileTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data.</td></tr>`;
                    });
            };

            function renderTable(data) {
                fileTableBody.innerHTML = "";
                if (!data?.length) {
                    fileTableBody.innerHTML = `<tr class="border border-gray-300 bg-white"><td colspan="8" class="border border-gray-300 px-4 py-2 text-center">No reports found.</td></tr>`;
                    return;
                }
                 

                data.forEach((file, index) => {
                    let filePaths = JSON.parse(file?.path) || [];
                    let formattedPaths = filePaths.join(', ');
                    let date = new Date(file.created_at);
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
                    let status0Count = 0;
                    let status1Count = 0;

                    file.report_data?.forEach(report => {
                        if (report.status === 0) {
                            status0Count++;
                        } else if (report.status === 1) {
                            status1Count++;
                        }
                    });

                    const shortenedPath = file.description.length > 15 ? file.description.slice(0, 17) + "..." : file.description;
                    let totalComms = file.report_data?.reduce((sum, report) => sum + (report.status || 0), 0) || 0;
                    let totalComments = file.report_data?.reduce((sum, report) => sum + (report.comment_list?.length || 0), 0) || 0;
                    const row = `
                        <tr class="border border-gray-300 bg-white">
                            <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                            <td class="border border-gray-300 px-4 py-2">${file.title}</td> 
                            {{-- <td class="border border-gray-300 px-4 py-2">${file.name}</td> 
                            <td class="border border-gray-300 px-4 py-2">${shortenedPath}</td> --}}
                            <td class="border border-gray-300 px-4 py-2">${file.actual_time}</td>
                             <td class=" px-4 py-2 flex gap-3" > <p class="bg-green-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${status1Count}</p> <p class="bg-orange-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${status0Count}</p> <p class="bg-red-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${totalComments}</p></td>
                            {{-- <td class="border border-gray-300 px-4 py-2">${shortenedPath}</td> --}}
                            <td class="border border-gray-300 px-4 py-2">${formattedDateTime}</td> 
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="http://127.0.0.1:8000/report_view/${file.id}" 
                                   target="_blank" 
                                    rel="noopener noreferrer"
                                    class="view-btn py-2 rounded-md  mr-3"
                                    style="padding-block: 4px;">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button data-id="${file.id}" data-name="${file.name}" data-title="${file.title}" data-actual_time="${file.actual_time}" data-description="${file.description}" data-paths='${JSON.stringify(filePaths)}' class="edit-btn py-1 rounded-md"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button data-id="${file.id}" class="delete-btn py-1 ml-2"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>`;
                    fileTableBody.innerHTML += row;
                });
            }

            function renderPagination(data) {
                paginationContainer.innerHTML = '';

                if (data.total <= data.per_page) return;

                // Previous Button
                if (data.prev_page_url) {
                    paginationContainer.innerHTML += `<button onclick="fetchFileData(${data.current_page - 1})" class="px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600">Previous</button>`;
                }

                // Page Numbers
                for (let i = 1; i <= data.last_page; i++) {
                    paginationContainer.innerHTML += `<button onclick="fetchFileData(${i})" class="px-3 py-1 mx-1 ${i === data.current_page ? 'bg-blue-600 text-white' : 'bg-gray-300'} rounded-md hover:bg-blue-700">${i}</button>`;
                }

                // Next Button
                if (data.next_page_url) {
                    paginationContainer.innerHTML += `<button onclick="fetchFileData(${data.current_page + 1})" class="px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600">Next</button>`;
                }
            }

            //  Event Listeners to fetch data when filters change
            if (filterDateInput) filterDateInput.addEventListener('change', () => fetchFileData(1));

            //  Initial Data Load
            fetchUserData();
            fetchFileData(1);
        });



        document.addEventListener('DOMContentLoaded', function() {
            const addFileButton = document.getElementById('add-more');
            const fileModal = document.getElementById('fileModal');
            const closeFileModalButtons = document.querySelectorAll('#close-file-modal');;
            const fileInputContainer = document.getElementById('file-input-container');
            const fileTableBody = document.getElementById('file-table-body');
            const modalForm = fileModal.querySelector('form');

            let isEditing = false;
            let editId = null;

            // Open modal for adding new entry
            addFileButton?.addEventListener('click', () => {
                isEditing = false;
                editId = null;
                modalForm.reset();
                document.getElementById('modal-title').textContent = "Create Daily Report";
                fileInputContainer.innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" name="file_path[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="/path/to/file.txt" required>
                    <p>
                    <button type="button" class="add-input-btn text-2xl text-blue-500" onclick="addInput()">+</button>
                </div>`;

                fileModal.classList.remove('hidden');
                fileModal.classList.add('flex');
            });

            // Close modal
            closeFileModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    fileModal.classList.add('hidden');
                    fileModal.classList.remove('flex');
                });
            });

            // Function to add new input field dynamically
           window.addInput = function(value = '') {
                const inputWrapper = document.createElement('div');
                inputWrapper.classList.add('flex', 'items-center', 'space-x-2', 'mt-2');

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'file_path[]';
                newInput.value = value;
                newInput.placeholder = '/path/to/file.txt';
                newInput.classList.add(
                    'mt-1', 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 
                    'rounded-md', 'shadow-sm', 'focus:outline-none', 'focus:ring-indigo-500', 
                    'focus:border-indigo-500', 'sm:text-sm', 'text-black'
                );

                const removeButton = document.createElement('button');
                removeButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>`;
                removeButton.type = 'button';
                removeButton.classList.add('text-sm', 'text-blue-500', 'hover:text-blue-700', 'focus:outline-none');
                removeButton.addEventListener('click', () => inputWrapper.remove());

                // Add error message container
                const errorMessage = document.createElement('p');
                errorMessage.classList.add('text-red-500', 'text-xs', 'mt-1', 'hidden');
                errorMessage.id = `file-path-error-${Date.now()}`; // Unique ID for each error message

                inputWrapper.append(newInput, removeButton, errorMessage);
                fileInputContainer.appendChild(inputWrapper);

                // Clear error on input change
                newInput.addEventListener('input', () => {
                    errorMessage.classList.add('hidden');
                });
            };

            // Fetch file data and populate table
            function fetchFileData() {
                fetch('/all-data')
                    .then(response => response.json())
                    .then(data => {
                        fileTableBody.innerHTML = ""; // Clear table before inserting new data

                        if (data.length === 0) {
                            fileTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500 py-4">No file paths uploaded yet.</td></tr>`;
                            return;
                        }

                        data.reports.data.forEach((file, index) => {
                            
                            let filePaths = JSON.parse(file?.path) || [];
                            let formattedPaths = filePaths?.join(', ');
                            let date = new Date(file.created_at);
                            let formattedDateTime = date.toLocaleDateString('en-IN', {
                                    month: 'short'
                                    , day: '2-digit'
                                    , year: 'numeric'
                                }).replace(',', '').replace(' ', '/') +
                                ' ' + date.toLocaleTimeString('en-IN', {
                                    hour: '2-digit'
                                    , minute: '2-digit'
                                    , hour12: true
                                });
                            {{-- const shortenedPath = file.description.length > 15 ?
                                file.description.slice(0, 17) + "..." :
                                file.description; --}}
                            let status0Count = 0;
                            let status1Count = 0;

                            file.report_data?.forEach(report => {
                                if (report.status === 0) {
                                    status0Count++;
                                } else if (report.status === 1) {
                                    status1Count++;
                                }
                            });

                            const shortenedPath = file.description.length > 15 ? file.description.slice(0, 17) + "..." : file.description;
                            let totalComms = file.report_data?.reduce((sum, report) => sum + (report.status || 0), 0) || 0;
                            let totalComments = file.report_data?.reduce((sum, report) => sum + (report.comment_list?.length || 0), 0) || 0;
                    
                            const row = ` 
                            <tr class="border border-gray-300 bg-white">
                                <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                                <td class="border border-gray-300 px-4 py-2">${file.title}</td> 
                                 {{-- <td class="border border-gray-300 px-4 py-2">${file.name}</td> 

                                <td class="border border-gray-300 px-4 py-2">${shortenedPath}</td> --}}
                                <td class="border border-gray-300 px-4 py-2">${file.actual_time}</td>
                                <td class="px-4 py-2 flex gap-3 " > <p class="bg-green-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${status1Count}</p> <p class="bg-orange-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${status0Count}</p> <p class="bg-red-500 text-white rounded-xl px-4 py-1 text-center min-w-[3rem]">${totalComments}</p></td>
                                {{-- <td class="border border-gray-300 px-4 py-2">${shortenedPath}</td> --}}
                                <td class="border border-gray-300 px-4 py-2">${formattedDateTime}</td> 
                                <td class="border border-gray-300 px-4 py-2">
                                 
                                <a href="http://127.0.0.1:8000/report_view/${file.id}" 
                                   target="_blank" 
                                    rel="noopener noreferrer"
                                    class="view-btn py-2 rounded-md  mr-3"
                                    style="padding-block: 4px;">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button data-id="${file.id}" data-name="${file.name}" data-title="${file.title}" data-description="${file.description}" data-actual_time="${file.actual_time}" data-paths='${JSON.stringify(filePaths)}' class="edit-btn py-1 rounded-md"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button data-id="${file.id}" class="delete-btn py-1 ml-2"><i class="fa-solid fa-trash"></i></button>
                                    

                                </td>
                            </tr>`;
                            fileTableBody.innerHTML += row;
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        fileTableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">Failed to load data.</td></tr>`;
                    });
            }

           
            document.addEventListener('click', function(event) {
                const editButton = event.target.closest('.edit-btn');
                if (editButton) {
                    isEditing = true;
                    editId = editButton.dataset.id;
                    document.getElementById('modal-title').textContent = "Edit Daily Report";
                    
                    const nameElement = document.getElementById('name');
                    if (nameElement) {
                        nameElement.innerText = editButton.dataset.name;
                    }
                    
                    document.getElementById('title').value = editButton.dataset.title;
                    document.getElementById('description').value = editButton.dataset.description;
                    document.getElementById('actual_time').value = editButton.dataset.actual_time;

                    fileInputContainer.innerHTML = ''; // Clear previous inputs
                    const filePaths = JSON.parse(editButton.dataset.paths);
                    filePaths.forEach((path, index) => {
                        if (index === 0) {
                            // First input: Only "+" button
                            fileInputContainer.innerHTML += `
                            <div class="flex items-center space-x-2">
                                <input type="text" name="file_path[]" value="${path}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="/path/to/file.txt" required>
                                <button type="button" class="add-input-btn text-2xl text-blue-500" onclick="addInput()">+</button>
                            </div>`;
                        } else {
                            // Additional inputs: Include "X" button
                            addInput(path);
                        }
                    });

                    fileModal.classList.remove('hidden');
                    fileModal.classList.add('flex');
                }
            });

            // DELETE FUNCTION WITH FIX
            document.addEventListener('click', async function(event) {
                const deleteButton = event.target.closest('.delete-btn');
                if (deleteButton) {
                    const id = deleteButton.dataset.id;
                    if (confirm('Are you sure you want to delete this file entry?')) {
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            const response = await fetch(`/delete/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (!response.ok) throw new Error("Failed to delete");

                            const data = await response.json();
                            alert(data.message);
                            fetchFileData(); // Refresh file list
                        } catch (error) {
                            console.error("Error deleting file:", error);
                            alert("Failed to delete file. Please try again.");
                        }
                    }
                }
            });


            // Handle Form Submission (Add/Edit)
           modalForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            let isValid = true;

            // Reset previous error messages
            document.getElementById('title-error').classList.add('hidden');
            document.getElementById('description-error').classList.add('hidden');
            document.getElementById('actual_time-error').classList.add('hidden');
            document.querySelectorAll('[id^="file-path-error-"]').forEach(error => error.classList.add('hidden'));

            // Validate Title
            const title = document.getElementById('title').value.trim();
            if (title === '') {
                document.getElementById('title-error').innerText = 'Title is required.';
                document.getElementById('title-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate Description
            const description = document.getElementById('description').value.trim();
            if (description === '') {
                document.getElementById('description-error').innerText = 'Description is required.';
                document.getElementById('description-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate Actual Time
            const actual_time = document.getElementById('actual_time').value.trim();
            if (actual_time === '') {
                document.getElementById('actual_time-error').innerText = 'Actual Time On Task is required.';
                document.getElementById('actual_time-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate File Paths
            const filePathInputs = fileInputContainer.querySelectorAll('input[name="file_path[]"]');
            const filePaths = Array.from(filePathInputs).map(input => input.value.trim());

            if (filePaths.some(path => path === '')) {
                document.getElementById('file-error').innerText = 'All file paths must be filled.';
                document.getElementById('file-error').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) return; // Stop form submission if validation fails

            // Proceed with Form Submission
            const formData = new FormData(modalForm);
            formData.append('file_path', JSON.stringify(filePaths));
             
            const url = isEditing ? `/update/${editId}` : '{{ route("store.file.path") }}';
            const method = 'POST';
             
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json'  
                    },
                    body: JSON.stringify(Object.fromEntries(formData))  
                });

                const data = await response.json();
                
                if (!data.success) {
                    if (data.invalid_paths && data.invalid_paths.length > 0) {
                        filePathInputs.forEach((input, index) => {
                            if (data.invalid_paths.includes(input.value.trim())) {
                                let errorMessage = input.parentElement.querySelector('[id^="file-path-error-"]');
                                if (!errorMessage) {
                                    errorMessage = document.createElement('p');
                                    errorMessage.classList.add('text-red-500', 'text-xs', 'mt-1');
                                    errorMessage.id = `file-path-error-${Date.now()}`;
                                    input.parentElement.appendChild(errorMessage);
                                }
                                errorMessage.innerText = 'Invalid file path.';
                                errorMessage.classList.remove('hidden');
                            }
                        });
                    } else {
                        document.getElementById('file-error').innerText = data?.path ;
                        document.getElementById('file-error').classList.remove('hidden');
                    }
                   
                }
                if (!response.ok) {
                throw data; 
                }

                alert(isEditing ? 'File updated successfully!' : 'File added successfully!');
                fetchFileData();
                fileModal.classList.add('hidden');

            } catch (error) {
                console.error("Error submitting form:", error);
                if (error.errors) {
                    document.getElementById('title-error').innerText = error.errors.title ? error.errors.title[0] : '';
                    document.getElementById('title-error').classList.remove('hidden');
                    document.getElementById('description-error').innerText = error.errors.description ? error.errors.description[0] : '';
                    document.getElementById('description-error').classList.remove('hidden');
                    document.getElementById('actual_time-error').innerText = error.errors.actual_time ? error.errors.actual_time[0] : '';
                    document.getElementById('actual_time-error').classList.remove('hidden');
                } else {
                    console.error("Error submitting form:", error);
                }
            }
        });

            
        });

    </script>

</x-app-layout>
