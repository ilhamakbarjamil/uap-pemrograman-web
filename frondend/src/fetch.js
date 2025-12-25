// fetch.js (REVISI FINAL - PERBAIKAN TAMPILAN KARTU)

document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Konfigurasi Kunci API ---
    const NEWS_API_KEY = 'a286c83b898a4608b5110aadf3bf017d'; 
    
    // --- 2. Fungsi untuk Bagian Berita Parfum ---
    async function loadPerfumeNews() {
        const newsList = document.getElementById('news-list'); 
        newsList.innerHTML = '<p class="col-span-2 text-center text-yellow-400 font-semibold">Mencari artikel mendalam seputar wewangian dan tren parfum...</p>';

        // Menghapus batasan SOURCES agar News API mencari di semua sumber.
        const SOURCES = ''; 
        
        // Melonggarkan QUERY (menggunakan OR) dan menggunakan tanda kutip untuk presisi tinggi.
        const QUERY = '"best perfume" OR "cologne review" OR "fragrance trend" OR "scent profile" OR "perfume collection"'; 
        const LANGUAGE = 'en'; 
        
        // Hapus &sources=${SOURCES} dari URL karena SOURCES dikosongkan.
        const API_URL_NEWS = `https://newsapi.org/v2/everything?q=${QUERY}&language=${LANGUAGE}&sortBy=publishedAt&apiKey=${NEWS_API_KEY}&pageSize=4`;

        try {
            const response = await fetch(API_URL_NEWS); 
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(`[${response.status}] ${errorData.message || 'Gagal memuat berita.'}`);
            }
            
            const data = await response.json();
            const articles = data.articles;

            if (articles.length === 0) {
                // Pesan diperbarui
                newsList.innerHTML = '<p class="col-span-2 text-center text-gray-500">Saat ini tidak ada artikel parfum yang sangat spesifik. Filter terlalu ketat.</p>';
                return;
            }

            newsList.innerHTML = ''; 

            articles.forEach(article => {
                const date = new Date(article.publishedAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                
                // Menentukan URL gambar atau placeholder
                const imageUrl = article.urlToImage 
                    ? article.urlToImage 
                    : 'https://placehold.co/100x100/374151/FFFFFF?text=SCENT'; // Placeholder tema gelap
                
                // Struktur card diubah menjadi flex row pada layar md ke atas
                const card = `
                    <div class="bg-gray-800 rounded-xl shadow-md p-6 flex flex-col h-full">
                        <img src="${imageUrl}"
                            alt="${article.title}"
                            class="rounded-lg mb-4 mx-auto h-48 object-cover bg-gray-700 w-full"
                            onerror="this.onerror=null; this.src='https://placehold.co/300x192/374151/FFFFFF?text=SCENT';">
                        <h4 class="font-bold text-lg mb-2 text-yellow-400 line-clamp-2 text-center">${article.title}</h4>
                        <p class="text-gray-300 mb-3 text-sm line-clamp-3 text-justify">${article.description || 'Tidak ada ringkasan tersedia.'}</p>
                        <div class="flex flex-col gap-2 mt-auto pt-2 border-t border-gray-700">
                            <span class="text-yellow-400 font-semibold text-xs">${date} | Sumber: ${article.source.name}</span>
                            <a href="${article.url}" target="_blank" rel="noopener noreferrer" class="block w-full bg-yellow-500 text-gray-900 py-2 rounded-lg text-center font-semibold hover:bg-yellow-400 transition">Baca Selengkapnya &rarr;</a>
                        </div>
                    </div>
                `;
                
                newsList.innerHTML += card; 
            });

        } catch (error) {
            console.error('Error loading news:', error);
            newsList.innerHTML = `<p class="col-span-2 text-center text-red-500">Gagal memuat berita: ${error.message}. Harap verifikasi kunci API dan kuota harian Anda.</p>`;
        }
    }

    loadPerfumeNews();
});
