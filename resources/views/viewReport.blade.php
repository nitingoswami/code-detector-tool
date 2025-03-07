<x-app-layout>
    <main class="bg-gray-900 text-white min-h-screen p-6">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-4">Report Details</h2>

            <!-- Report Main Info -->
            <div class="mb-6">
                <p class="text-lg"><strong>Name:</strong> <span id="reportName" class="font-semibold"></span></p>
                <p class="text-lg"><strong>Title:</strong> <span id="reportTitle" class="font-semibold"></span></p>
                <p class="text-lg"><strong>Description:</strong> <span id="reportDescription" class="font-semibold"></span></p>
                <p class="text-lg"><strong id="Date"></strong> <span id="reportDate" class="font-semibold"></span></p>
            </div>

            <!-- Container for Dynamic Tables -->
            <div id="tablesContainer"></div>
        </div>
    </main>
    <div id="fileModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-md w-1/3 shadow-lg">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-semibold text-gray-900 mb-2 text-center flex-grow text-center">Add Comment</h3>
                <button type="button" id="close-file-modal" class="text-gray-500 hover:text-gray-700">
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
                    <button type="button" id="close-file-modal" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Close</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
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
            // Populate report details
            document.getElementById('reportName').textContent = data.dailyreport.name || "N/A";
            document.getElementById('reportTitle').textContent = data.dailyreport.title || "N/A";
            document.getElementById('reportDescription').textContent = data.dailyreport.description || "N/A";

            // Clear previous content
            const tablesContainer = document.getElementById('tablesContainer');
            tablesContainer.innerHTML = ""; 

            // Convert paths to a Set for quick lookup
            const validPaths = new Set(JSON.parse(data.dailyreport.path));

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
            tablesContainer.innerHTML = `<p class="text-center text-gray-400">No report data available.</p>`;
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

    const modal = document.getElementById("fileModal");
    let selectedReportId = null; // Store the selected report ID
    
    function attachCommentButtonListeners() {
        const commentButtons = document.querySelectorAll(".comment-button");
        const closeModalButtons = document.querySelectorAll("#close-file-modal");

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

    </script>
</x-app-layout>