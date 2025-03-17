<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">

        {{-- @if (Auth::user()->user_role=="admin") --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3 pl-2">
            <div class="flex space-x-3">
            </div> 
            <ul id="project-list-parent">
            </ul>
        </div>
        {{-- @endif --}}

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchProjectData();
        });


        async function fetchProjectData() {
            try {
                const response = await fetch('/project-data'); // Fetch data from the project-data endpoint

                // Check if the response is successful
                if (!response.ok) {
                    
                }

                const data = await response.json(); // Parse the JSON response
                if (data.success) {
                    const projects = data.projects; // Access the projects array from the respons

                    const listBody = document.getElementById('project-list-parent');
                    listBody.innerHTML = ''; // Clear the loading message

                    projects.forEach((project, index) => {
                        let newCount=0;
                        let downoaldedCount=0;
                        project.daily_reports.forEach((project, index) => {
                                   
                                    if (project.task_status === "new") {
                                            newCount++;
                                        } else if (project.task_status === "complete") {
                                            downoaldedCount++;
                                        }
                                
                                
                        })
                        const listItem = document.createElement('li');

                        listItem.innerHTML = `
                               <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3 pl-2">
                                    <div class="bg-white flex items-center justify-between dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-tr-md ">
                                        <div class="p-6 text-gray-900 dark:text-gray-100 min-w-[200px]">
                                            ${project.project_name}
                                        </div>
                                        

                                        <div class="p-6 flex items-center ">
                                        <div class="px-4 py-2 flex gap-3 mr-3" > <p class="bg-orange-500 text-white rounded-2xl px-4 py-1 text-center min-w-[4rem]">${newCount}</p><p class="bg-green-500 text-white rounded-2xl px-4 py-1 text-center min-w-[4rem]">${downoaldedCount}</p>  </div>
                                            <a href='http://127.0.0.1:8000/app/?data-id=${project.id}&data-name=${project.project_name}' class="cursor-pointer" class="view-btn"><i class="fa-solid fa-eye text-white"></i></a>
                                        </div>
                                         
                                    </div>
                                </div>`;
                        listBody.appendChild(listItem);
                    });
                } else {
                    // If success is false or Project list is empty, handle error from backend 
                    const listBody = document.getElementById('project-list-parent');
                    if (!data.projects) {

                        listBody.innerHTML = '<li><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3 pl-2"><div class="bg-white flex items-center justify-between dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900 dark:text-gray-100"><div colspan="8" class="text-center text-red-500 font-semibold text-md py-4">No Records were found !</div></div><div class="p-3"></div></div></div></li>';
                    } else {
                        console.error('Error:', data.error);
                        listBody.innerHTML = '<li><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3 pl-2"><div class="bg-white flex items-center justify-between dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900 dark:text-gray-100"><div colspan="8" class="text-center text-red-500 font-semibold text-md py-4">Failed to load data: ' + data.error + '</div></div><div class="p-3"></div></div></div></li>';
                    }
                }
            } catch (error) {
                console.error('Error fetching project data:', error);
                const listBody = document.getElementById('project-list-parent');
                listBody.innerHTML = '<li><div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-3 pl-2"><div class="bg-white flex items-center justify-between dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900 dark:text-gray-100"><div colspan="8" class="text-center text-red-500 font-semibold text-md py-4">' + error + '</div></div><div class="p-3"></div></div></div></li>';
            }
        }


        // Fetch project data when the page 

        document.addEventListener('DOMContentLoaded', fetchProjectData);


        document.addEventListener('DOMContentLoaded', function() {

            fetchProjectData();

            // Close Edit Project Modal
            document.querySelectorAll('#closeEditProjectModal').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('editProjectModal').classList.add('hidden');
                });
            });
        });
    </script>

</x-app-layout>