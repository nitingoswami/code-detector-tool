<x-app-layout>
    <x-slot name="header">
        <div class="d-flex w-100 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Upload File Path') }}
            </h2>
            <div>
                <button type="button" id="add-more" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Add More
                </button>
            </div>
        </div>
    </x-slot>
    
    <!-- Table to Display File Paths -->
    <div class="container mx-auto mt-6">
        #Team-004 | Fixed | Variable update from int to fload | 12 Mar 2025 | Start

     
       #    fileModal.classList.remove('flex');
       #     });
         
              <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">S no.</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">File Path</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date-time</th>
                </tr>
            </thead>
            <tbody id="file-table-body">
                <tr>
                    <td colspan="3" class="text-center text-gray-500 py-4">Loading...</td>
                </tr>
            </tbody>
        </table>

         #Team-004 | Fixed | Variable update from int to fload | 12 Mar 2025 | End

            
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">S no.</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">File Path</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date-time</th>
                </tr>
            </thead>
            <tbody id="file-table-body">
                <tr>
                    <td colspan="3" class="text-center text-gray-500 py-4">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Add More Files Modal -->
    <div id="fileModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white rounded-md p-6 w-1/3 max-w-md shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-900">Add More Files</h3>
            <form action="{{ route('store.file.path') }}" method="POST">
                @csrf
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text" id="description" name="description" class="block w-full rounded-md border text-black focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter description">

                <label for="file_path" class="block text-sm font-medium text-gray-700">File Path</label>
                <div id="file-input-container">
                    <div class="flex items-center space-x-2 mt-2">
                        <input type="text" name="file_path[]" class="block w-full rounded-md border text-black focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="/path/to/file.txt" required>
                        <button type="button" class="add-input-btn text-2xl text-blue-500" onclick="addInput()">+</button>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" id="close-file-modal" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Close</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                </div>
            </form>
        </div>
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addFileButton = document.getElementById('add-more');
            const fileModal = document.getElementById('fileModal');
            const closeFileModalButton = document.getElementById('close-file-modal');
            const fileInputContainer = document.getElementById('file-input-container');
            const fileTableBody = document.getElementById('file-table-body');


            addFileButton ? .addEventListener('click', () => {
                fileModal.classList.remove('hidden');
                fileModal.classList.add('flex');
            });

            closeFileModalButton ? .addEventListener('click', () => {
                fileModal.classList.add('hidden');
                fileModal.classList.remove('flex');
            });
// #Team-004 | Fixed | Variable update from int to fload | 07 Mar 2025 | Start

    //   closeFileModalButton ? .addEventListener('click', () => {
      //         fileModal.classList.add('hidden');
       //        fileModal.classList.remove('flex');
       //  });
         
            const newInput = document.createElement('input');
                    newInput.type = 'text';
                newInput.name = 'file_path[]';

                        // #Team-004 | Fixed | Variable update from int to fload | 07 Mar 2025 | End

            window.addInput = function() {
                const inputWrapper = document.createElement('div');
                inputWrapper.classList.add('flex', 'items-center', 'space-x-2', 'mt-2');

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'file_path[]';
                newInput.placeholder = '/path/to/file.txt';
                newInput.classList.add('block', 'w-full', 'rounded-md', 'border', 'text-black', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');

                const removeButton = document.createElement('button');
                removeButton.innerHTML = '✖';
                removeButton.type = 'button';
                removeButton.classList.add('text-sm', 'text-blue-500', 'hover:text-blue-700', 'focus:outline-none');
                removeButton.addEventListener('click', () => inputWrapper.remove());

                inputWrapper.append(newInput, removeButton);
                fileInputContainer.appendChild(inputWrapper);
            };

                 <div>
           #Team-004 | Fixed | Variable update from int to fload | 13 Mar 2025 | Start

     
       #    fileModal.classList.remove('flex');
       #     });
         
             newInput.name = 'file_path[]';

         #Team-004 | Fixed | Variable update from int to fload | 13 Mar 2025 | End

            


            function fetchFileData() {
                fetch('/all-data')
                    .then(response => response.json())
                    .then(data => {
                        fileTableBody.innerHTML = "";
                    // #Team-004 | Update | Variable update from int to fload | 05 Mar 2025 | Start

                       removeButton.innerHTML = '✖';
                removeButton.type = 'button';
                removeButton.classList.add('text-sm', 'text-blue-500', 'hover:text-blue-700', 'focus:outline-none');
                removeButton.addEventListener('click', () => inputWrapper.remove());

                       //let filePaths = JSON.parse(file.path);
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);

                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);
                         
                        //    let formattedPaths = filePaths.join(', ');sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                         //   let date = new Date(file.created_at);


                        // #Team-004 | Update | Variable update from int to fload | 05 Mar 2025 | End

                        data.report.forEach((file, index) => {
                            let filePaths = JSON.parse(file.path);
                            let formattedPaths = filePaths.join(', ');
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

                            const row = `
                    <tr class="border border-gray-300 bg-gray-200">
                        <td class="border border-gray-300 px-4 py-2">${index + 1}</td>
                        <td class="border border-gray-300 px-4 py-2">${file.description}</td>
                        <td class="border border-gray-300 px-4 py-2">${formattedPaths}</td>
                        <td class="border border-gray-300 px-4 py-2">${formattedDateTime}</td> 
                    </tr>
                `;
                            fileTableBody.innerHTML += row;
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        fileTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-red-500 py-4">Failed to load data.</td></tr>`;
                    });
            }

            fetchFileData();
        });

    </script>
    

</x-app-layout>
