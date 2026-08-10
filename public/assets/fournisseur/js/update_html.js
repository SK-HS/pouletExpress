const fs = require('fs');

const files = fs.readdirSync('.').filter(f => f.endsWith('.html'));

const toggleBtn = `            <button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all" id="theme-toggle">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl hidden dark:block">light_mode</span>
            </button>\n`;

const searchStr = `<button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-on-surface-variant text-xl md:text-2xl">notifications</span>
            </button>`;

for (const file of files) {
  let content = fs.readFileSync(file, 'utf8');
  if (content.includes(searchStr) && !content.includes('id="theme-toggle"')) {
    content = content.replace(searchStr, toggleBtn + searchStr);
    fs.writeFileSync(file, content);
    console.log('Updated ' + file);
  } else {
    console.log('Skipped ' + file);
  }
}
