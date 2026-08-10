const fs = require('fs');

const tailwindConfigFile = 'js/tailwind-config.js';
const mainCssFile = 'css/main.css';

let configContent = fs.readFileSync(tailwindConfigFile, 'utf8');

// Extract the colors object string
const colorsMatch = configContent.match(/"colors":\s*({[\s\S]*?}),\n\s*"borderRadius"/);
if (!colorsMatch) {
    console.error("Could not find colors in tailwind config");
    process.exit(1);
}

const colorsString = colorsMatch[1];
// Parse it as JSON (we need to make sure it's valid JSON, it seems to be in the file)
let colorsObj;
try {
    colorsObj = JSON.parse(colorsString);
} catch (e) {
    console.error("Failed to parse colors JSON", e);
    process.exit(1);
}

let rootVars = ":root {\n";
let darkVars = ".dark {\n";

// Helper to determine dark mode color
function getDarkColor(name, original) {
    // Map to dark equivalents if they exist
    const mappings = {
        "surface": "#1f2937", // inverse-surface
        "surface-container-lowest": "#111827", 
        "surface-container-low": "#1f2937",
        "surface-container": "#374151",
        "surface-container-high": "#4b5563",
        "surface-container-highest": "#6b7280",
        "surface-bright": "#374151",
        "surface-dim": "#111827",
        "background": "#111827", // dark background
        
        "on-surface": "#f9fafb", // inverse-on-surface
        "on-surface-variant": "#d1d5db",
        "on-background": "#f9fafb",
        
        "outline": "#6b7280",
        "outline-variant": "#4b5563",
        
        "primary": "#34d399", // lighter primary for dark mode
        "on-primary": "#022c22",
        "primary-container": "#064e3b",
        "on-primary-container": "#a7f3d0",
        
        "secondary": "#fbbf24",
        "on-secondary": "#451a03",
        "secondary-container": "#78350f",
        "on-secondary-container": "#fde68a",
        
        "tertiary": "#fb923c",
        "on-tertiary": "#431407",
        "tertiary-container": "#7c2d12",
        "on-tertiary-container": "#fed7aa",
        
        "error": "#f87171",
        "on-error": "#450a0a",
        "error-container": "#7f1d1d",
        "on-error-container": "#fca5a5"
    };

    if (mappings[name]) {
        return mappings[name];
    }
    
    // For unmapped ones, just return the original for now (or try to darken/lighten conceptually)
    return original;
}

let newColorsObj = {};

for (const [key, value] of Object.entries(colorsObj)) {
    const varName = `--color-${key}`;
    rootVars += `    ${varName}: ${value};\n`;
    darkVars += `    ${varName}: ${getDarkColor(key, value)};\n`;
    
    newColorsObj[key] = `var(${varName})`;
}

rootVars += "}\n";
darkVars += "}\n";

// Update tailwind config
const newColorsString = JSON.stringify(newColorsObj, null, 16).replace(/\n/g, '\n            ');
const newConfigContent = configContent.replace(colorsString, newColorsString.trim());
fs.writeFileSync(tailwindConfigFile, newConfigContent);
console.log("Updated tailwind-config.js");

// Update main.css
let cssContent = fs.readFileSync(mainCssFile, 'utf8');

// Remove hardcoded body background
cssContent = cssContent.replace(/background-color:\s*#f8faf8;/g, 'background-color: var(--color-background);');
cssContent = cssContent.replace(/color:\s*#1f2937;/g, 'color: var(--color-on-surface);');
// Add variables at the top
cssContent = rootVars + darkVars + cssContent;
fs.writeFileSync(mainCssFile, cssContent);
console.log("Updated main.css");
