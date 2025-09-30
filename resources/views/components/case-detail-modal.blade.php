<!-- Case Detail Modal -->
<div id="caseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Kasus</h3>
            <button onclick="closeCaseModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="modalContent" class="mt-4">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                <span class="ml-2 text-gray-600">Memuat data...</span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end pt-4 border-t border-gray-200 space-x-3">
            <button onclick="closeCaseModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                Tutup
            </button>
            <a id="modalViewFullLink" href="#"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Lihat Detail Lengkap
            </a>
        </div>
    </div>
</div>
