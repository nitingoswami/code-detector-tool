<x-app-layout>
    <main class="bg-gray-900 text-white min-h-screen p-6">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-4">Report Details</h2>
            

            <!-- Report Main Info -->
            <div class="flex justify-between">
            <div class="mb-6">
                <div class='flex h-[30px]'> <p class="text-lg"><strong>Name:</strong> <span id="reportName" class="font-semibold"></span></p> <button  style="margin-bottom: 2rem;" class="task-status px-4 py-2 h-10 bg-blue-600 text-white rounded-md hover:bg-blue-700 ml-32" ></button></div>
                <p class="text-lg"><strong>Title:</strong> <span id="reportTitle" class="font-semibold"></span></p>
                <p class="text-lg"><strong>Description:</strong> <span id="reportDescription" class="font-semibold"></span></p>
                <p class="text-lg"><strong id="Date"></strong> <span id="reportDate" class="font-semibold"></span></p>
            </div >
            @if (Auth::user()->user_role!="admin")
            <div class='flex items-end ml-3'><button id="add-more" style="margin-bottom: 2rem;" class="px-4 py-2 h-10 bg-blue-600 text-white rounded-md hover:bg-blue-700" ></button></div>
            {{-- <div class='flex items-end ml-3'><button id="add-more" style="margin-bottom: 2rem;" class="px-4 py-2 h-10 bg-blue-600 text-white rounded-md hover:bg-blue-700" >Edit File Path</button></div> --}}
            </div>
            @endif
             @if (Auth::user()->user_role=="admin")
            <div class='flex items-end ml-3'><button  style="margin-bottom: 2rem;" class="" ></button></div>
           
            </div>
            @endif


            <!-- Container for Dynamic Tables -->
            <div id="tablesContainer"></div>
        </div>
    </main>
    <div id="fileModal1" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-md w-1/3 shadow-lg">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow text-center">Add Comment</h3>
                <button type="button" id="close-file-modal2" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
           <form id="comment-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea id="comment" name="comment"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter your comment" rows="4" required></textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="comment-error"></p>
                </div>

                <div class="flex justify-center mt-6 space-x-2">
                    <button type="button" id="close-file-modal2" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Close</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>

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
                
                @csrf

               
                

                 <!-- File Path Inputs -->
                @if (Auth::user()->user_role!="admin")
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
                @endif
                

                <!-- Buttons -->
                <div class="flex justify-center mt-6 space-x-2">
                    <button type="button" id="close-file-modal" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Close</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Performance Rating Modal -->
    <div id="performanceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <div class="flex items-center justify-between w-full">
            <h3  class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow text-center">Performance Rating</h3>
            <button type="button" id="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            </div>
            
            <label for="rating" class="block text-sm font-medium">Select Rating:</label>
            <select id="rating" class="w-full p-2 border rounded mt-2">
                <option value="1">1 </option>
                <option value="2">2 </option>
                <option value="3">3 </option>
                <option value="4">4 </option>
                <option value="5">5 </option>
                <option value="6">6 </option>
                <option value="7">7 </option>
                <option value="8">8 </option>
                <option value="9">9 </option>
                <option value="10">10 </option>
            </select>
            
            <div class="flex justify-center mt-4">
                <button id="cancelModal" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 mr-2">Cancel</button>
                <button id="submitRating" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
            </div>
        </div>
    </div>






    <script>
    document.addEventListener('DOMContentLoaded', async function fetchReportDetails () {
    const url = window.location.href;
    const id = url.split('/').pop(); // Extracts the last segment of the URL
    
    try {
        const response = await fetch(`/view/${id}`); // Fetch data from API

        if (!response.ok) {
            throw new Error("Failed to fetch report data");
        }

        const data = await response.json();

          
        if (data.success) {
             window.dailyReportID = data.dailyreport.id;
            window.filePaths = JSON.parse(data.dailyreport.path) || [];

            @if (Auth::user()->user_role=="admin")
            document.querySelector('.task-status').setAttribute('disabled', true);
            document.querySelector('.task-status').classList.add('cursor-not-allowed');
            @endif
                   
             document.querySelector('.task-status').innerHTML = data.dailyreport.task_status=="complete"?"Completed":"New";
            // Populate report details
            document.getElementById('reportName').textContent = data.dailyreport.name || "N/A";
            document.getElementById('reportTitle').textContent = data.dailyreport.title || "N/A";
            document.getElementById('reportDescription').textContent = data.dailyreport.description || "N/A";

            // Clear previous content
            const tablesContainer = document.getElementById('tablesContainer');
            tablesContainer.innerHTML = ""; 

            // Convert paths to a Set for quick lookup
            const validPaths = new Set(JSON.parse(data.dailyreport.path));
             @if (Auth::user()->user_role!="admin")  
              if (data.report.report_data.length > 0) {
                window.addFileButton.innerText="Edit File Path";
                window.addFileButton.removeAttribute('id');
                window.addFileButton.classList.add('edit-btn');
                window.addFileButton.setAttribute('data-id', (window.dailyReportID));
                window.addFileButton.setAttribute('data-paths', JSON.stringify(window.filePaths));
            }
            @endif
            // Render each report as a separate table
            data.report.report_data.forEach((report, index) => {
            if (validPaths.has(report.file_path)) {
                let date = new Date(report.created_at);
                let formattedDateTime = date.toLocaleDateString('en-IN', {
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                });
                document.getElementById('Date').textContent = formattedDateTime?"Date:":"";
                document.getElementById('reportDate').textContent = formattedDateTime;
                // Create table with comments section
                const tableHTML = `
                    <div class="mb-8">
                        <div >
                    <h3 class="bg-gray-200 text-lg text-black font-semibold pl-2 pt-2"
                        style="color: ${report.status ? 'green' : 'orange'};">
                        Path: ${report.file_path}
                    </h3>

                            <h3 class="bg-gray-200 text-lg text-black font-semibold pl-2">Type: ${report.type}</h3>
                            <h3 class="bg-gray-200 text-lg text-black font-semibold pl-2">Summary: ${report.summary}</h3>
                        </div>
                        <div class="overflow-x-auto max-h-[300px]">
                            <table class="w-full border border-gray-300 rounded-lg bg-white text-black overflow-y-auto">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">S No.</th>
                                        {{-- <th class="border border-gray-300 px-4 py-2">Type</th>
                                        <th class="border border-gray-300 px-4 py-2">Summary</th>
                                        <th class="border border-gray-300 px-4 py-2">Date</th> --}}
                                        <th class="border border-gray-300 px-4 py-2">Old Code</th>
                                        <th class="border border-gray-300 px-4 py-2">New Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white transition">
                                        <td class="border border-gray-300 px-4 py-2"><span>${index + 1}</span></td>
                                        {{-- <td class="border border-gray-300 px-4 py-2">${report.type}</td>
                                        <td class="border border-gray-300 px-4 py-2">${report.summary}</td>
                                        <td class="border border-gray-300 px-4 py-2">${report.date}</td> --}}
                                        <td class="border border-gray-300 px-4 py-2"><pre class="p-2 rounded">${report.old_code}</pre></td>
                                        <td class="border border-gray-300 px-4 py-2"><pre class="p-2 rounded">${report.new_code}</pre></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-4 p-4 bg-gray-800 text-white rounded">
                            ${report.performance_rating? `<h4 class="text-lg font-semibold">Performance Rating: ${report.performance_rating}</h4>` : ""}
                            <h4 class="text-lg font-semibold">Comments</h4>
                            <ul id="comments-${report.id}" class="list-disc pl-4">
                                ${report.comment_list && report.comment_list.length > 0 
                                    ? report.comment_list.map(comment => {
                                        let date = new Date(comment.created_at);
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

                                        return `<li class="mt-2">${comment.comment_text} - <span class="text-sm text-gray-400">${formattedDateTime}</span></li>`;
                                    }).join('')
                                    : '<li class="text-gray-400">No comments yet.</li>'
                                }
                            </ul>


                        </div>
                        @if (Auth::user()->user_role=="admin")
                        <div class="space-x-4 flex justify-end mt-3">
                            ${report.status 
                                ? `<button class="performance-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300" 
                                    data-id="${report.id}">
                                    Performance Rating
                                </button>` 
                                : ''}
                            <button class="comment-button bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-300" data-id="${report.id}">
                                Comment
                            </button>
                            <button class="mark-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300" data-id="${report.id}">
                                ${report.status ? 'Unmark' : 'Mark Done'}
                            </button>
                        </div>
                        @endif
                        
                    </div>
                `;

                tablesContainer.innerHTML += tableHTML; 
            }
        });

       


        // If no data is availabledata.report.report_data
        if (data.report.report_data.length === 0) {
            tablesContainer.innerHTML = `<p class="text-center text-gray-400 mt-4">No report data available.</p>`;
        }

            // Attach event listeners to dynamically generated "Comment" buttons
        attachCommentButtonListeners();
        }

        else {
            // Handle case where data.success is false
            document.getElementById('reportName').textContent = "N/A";
            document.getElementById('reportTitle').textContent = "N/A";
            document.getElementById('reportDescription').textContent = "N/A";
            document.getElementById('tablesContainer').innerHTML = `<p class="text-center text-gray-400">Report not found.</p>`;
        }
    } catch (error) {
        console.error("Error fetching report data:", error);
    }


    function fetchComments() {
        fetch("{{ route('admin.get.comment') }}")
            .then(response => response.json())
            .then(data => {

            })
            .catch(error => {
                console.error("Error fetching data:", error);
                
            });
    }
    fetchComments();
    window.fetchReportDetails = fetchReportDetails;
    
    

    });

    const modal = document.getElementById("fileModal1");
    let selectedReportId = null; // Store the selected report ID
    
    function attachCommentButtonListeners() {
        const commentButtons = document.querySelectorAll(".comment-button");
        const closeModalButtons = document.querySelectorAll("#close-file-modal2");

        commentButtons.forEach(button => {
            button.addEventListener("click", function () {
                selectedReportId = this.getAttribute("data-id"); // Store report ID
                modal.classList.remove("hidden"); // Show modal
            });
        });

        closeModalButtons.forEach(button => {
            button.addEventListener("click", function () {
                modal.classList.add("hidden");
            });
        });
    }

    const commentForm = document.getElementById('comment-form');
    commentForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (!selectedReportId) {
            alert("No report selected.");
            return;
        }

        const formData = new FormData(commentForm);
        const comment = formData.get('comment');

        try {
            const response = await fetch("{{ route('admin.store.comment') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    comment: comment, 
                    report_id: selectedReportId 
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('Comment submitted successfully');
                modal.classList.add('hidden');
                commentForm.reset();
                fetchReportDetails();
                // Append new comment to the related list
                const commentsList = document.getElementById(`comments-${selectedReportId}`);
                const newCommentHTML = `<li class="mt-2">${comment} - <span class="text-sm text-gray-400">Just now</span></li>`;
                if (commentsList) {
                    commentsList.innerHTML += newCommentHTML;
                   
                }
            } else {
                alert('Failed to submit comment: ' + result.message);
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
            alert('An error occurred while submitting the comment');
        }
    });

   

    document.addEventListener('click', async function (event) {
        if (event.target.classList.contains('mark-button')) {

            const reportId = event.target.getAttribute('data-id');


            if (!reportId) {
                alert("Invalid report ID.");
                return;
            }

            {{-- const confirmAction = confirm("Are you sure you want to mark this report as done?");
            if (!confirmAction) return; --}}

            try {
                const response = await fetch("{{ route('admin.update.status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ report_id: reportId })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.status);
                
                    fetchReportDetails();
                } else {
                    alert("Failed to update report status: " + result.message);
                }
            } catch (error) {
                console.error("Error updating report status:", error);
                alert("An error occurred while updating the report status.");
            }
        }
    });

    document.addEventListener('click', async function (event) {
        if (event.target.classList.contains('task-status')) {
          
           
              
            {{-- const confirmAction = confirm("Are you sure you want to mark this report as done?");
            if (!confirmAction) return; --}}

            try {
                const response = await fetch("{{ route('update.task.status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ report_id:  window.dailyReportID })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                   
                    fetchReportDetails()
                    
                } else {
                    alert("Failed to update report status: " + result.message);
                }
            } catch (error) {
                console.error("Error updating report status:", error);
                alert("An error occurred while updating the report status.");
            }
        }
    });



  
@if (Auth::user()->user_role!="admin")
 document.addEventListener('DOMContentLoaded', function() {
            window.addFileButton = document.getElementById('add-more');
            const fileModal = document.getElementById('fileModal');
            const closeFileModalButtons = document.querySelectorAll('#close-file-modal');
            @if (Auth::user()->user_role!="admin")
            const fileInputContainer = document.getElementById('file-input-container');
            @endif
            const fileTableBody = document.getElementById('file-table-body');
            const modalForm = fileModal.querySelector('form');

            let isEditing = false;
            let editId = null;
            window.addFileButton.innerText="Add File Path";
            // Open modal for adding new entry
            window.addFileButton?.addEventListener('click', () => {
                isEditing = false;
                editId = null;
                modalForm.reset();
                document.getElementById('modal-title').textContent = "Add File Path";
                @if (Auth::user()->user_role!="admin")
                fileInputContainer.innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" name="file_path[]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="/path/to/file.txt" required>
                    <p>
                    <button type="button" class="add-input-btn text-2xl text-blue-500" onclick="addInput()">+</button>
                </div>`;
                @endif
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
                @if (Auth::user()->user_role!="admin")
                fileInputContainer.appendChild(inputWrapper);
                @endif
                // Clear error on input change
                newInput.addEventListener('input', () => {
                    errorMessage.classList.add('hidden');
                });
            };

            // Fetch file data and populate table
            

           
             document.addEventListener('click', function(event) {
                const editButton = event.target.closest('.edit-btn');
                if (editButton) {
                    isEditing = true;
                    editId = editButton.dataset.id;
                    document.getElementById('modal-title').textContent = "Edit File Path";
                    
                    const nameElement = document.getElementById('name');
                    if (nameElement) {
                        nameElement.innerText = editButton.dataset.name;
                    }
                    
                   
                    @if (Auth::user()->user_role=="client")
                    fileInputContainer.innerHTML = ''; 
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
                    @endif


                    fileModal.classList.remove('hidden');
                    fileModal.classList.add('flex');
                }
            });

           


            // Handle Form Submission (Add/Edit)
           modalForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            let isValid = true;

            // Reset previous error messages
            document.querySelectorAll('[id^="file-path-error-"]').forEach(error => error.classList.add('hidden'));

           

            @if (Auth::user()->user_role!="admin")
            // Validate File Paths
            const filePathInputs = fileInputContainer.querySelectorAll('input[name="file_path[]"]');
            const filePaths = Array.from(filePathInputs).map(input => input.value.trim());

            if (filePaths.some(path => path === '')) {
                document.getElementById('file-error').innerText = 'All file paths must be filled.';
                document.getElementById('file-error').classList.remove('hidden');
                isValid = false;
            }
            @endif

            if (!isValid) return; // Stop form submission if validation fails

            // Proceed with Form Submission
            const formData = new FormData(modalForm);
            if (window.dailyReportID) {
                formData.append('dailyreport_id', window.dailyReportID);
            }
           
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
                fetchReportDetails()
                fileModal.classList.add('hidden');

            } catch (error) {
                console.error("Error submitting form:", error);

            }
        });

            
        });
    @endif   


   document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("performanceModal");
    const closeModal = document.getElementById("closeModal"); // X button
    const cancelModal = document.getElementById("cancelModal"); // Cancel button
    const submitRating = document.getElementById("submitRating");

    // Open modal on button click
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("performance-btn")) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
            modal.dataset.id = event.target.getAttribute("data-id"); // Store report ID
        }
    });

    // Close modal when clicking X or Cancel
    function closeModalHandler() {
        modal.classList.add("hidden");
    }

    closeModal.addEventListener("click", closeModalHandler);
    cancelModal.addEventListener("click", closeModalHandler); // Now Cancel button works!

    // Submit rating
    submitRating.addEventListener("click", function () {
        const reportId = modal.dataset.id; // Get stored report ID
        const rating = document.getElementById("rating").value;

        // Send AJAX request to save rating
        fetch('{{ route("admin.performanceRating.store") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ report_id: reportId, rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Performance Rating Saved Successfully!");
                closeModalHandler();
                fetchReportDetails()
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});


    </script>
</x-app-layout>