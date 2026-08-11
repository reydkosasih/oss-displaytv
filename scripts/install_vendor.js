const fs = require('fs');
const path = require('path');
const https = require('https');

// Create directories
const dirs = [
    'public/vendor/fontawesome/css',
    'public/vendor/fontawesome/webfonts',
    'public/vendor/sweetalert2',
    'public/vendor/jquery',
    'public/vendor/chartjs',
    'public/vendor/sortablejs'
];

dirs.forEach(d => fs.mkdirSync(d, { recursive: true }));

// Copy node_modules assets where available
fs.copyFileSync('node_modules/sweetalert2/dist/sweetalert2.all.min.js', 'public/vendor/sweetalert2/sweetalert2.all.min.js');
fs.copyFileSync('node_modules/jquery/dist/jquery.min.js', 'public/vendor/jquery/jquery.min.js');
fs.copyFileSync('node_modules/chart.js/dist/chart.umd.js', 'public/vendor/chartjs/chart.umd.js');
fs.copyFileSync('node_modules/sortablejs/Sortable.min.js', 'public/vendor/sortablejs/Sortable.min.js');
fs.copyFileSync('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/vendor/fontawesome/css/all.min.css');

// Function to download a file from URL
function downloadFile(url, dest) {
    return new Promise((resolve, reject) => {
        const file = fs.createWriteStream(dest);
        https.get(url, (response) => {
            if (response.statusCode === 301 || response.statusCode === 302) {
                return downloadFile(response.headers.location, dest).then(resolve).catch(reject);
            }
            if (response.statusCode !== 200) {
                return reject(new Error(`Failed to get '${url}' (${response.statusCode})`));
            }
            response.pipe(file);
            file.on('finish', () => {
                file.close(() => resolve());
            });
        }).on('error', (err) => {
            fs.unlink(dest, () => reject(err));
        });
    });
}

const fontFiles = [
    'fa-solid-900.woff2',
    'fa-solid-900.ttf',
    'fa-brands-400.woff2',
    'fa-brands-400.ttf',
    'fa-regular-400.woff2',
    'fa-regular-400.ttf',
    'fa-v4compatibility.woff2',
    'fa-v4compatibility.ttf'
];

async function main() {
    console.log('Downloading FontAwesome webfonts...');
    for (const font of fontFiles) {
        const url = `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/webfonts/${font}`;
        const dest = path.join('public/vendor/fontawesome/webfonts', font);
        try {
            await downloadFile(url, dest);
            console.log(`Downloaded: ${font}`);
        } catch (e) {
            console.warn(`Could not download ${font}: ${e.message}`);
        }
    }
    console.log('All local CDN vendor assets installed successfully!');
}

main();
