<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name= "csrf-token" content= "{{ csrf_token() }}" > 
<title>{{config('app.name')}}</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "primary-container": "#1b5e20",
                    "primary-fixed": "#acf4a4",
                    "on-primary-container": "#90d689",
                    "inverse-primary": "#91d78a",
                    "mtn-money": "#FFCC00",
                    "status-info": "#0277BD",
                    "secondary": "#835400",
                    "on-tertiary": "#ffffff",
                    "on-surface": "#191c1b",
                    "inverse-surface": "#2e3130",
                    "on-tertiary-container": "#e2bdb0",
                    "moov-money": "#0062A9",
                    "tertiary-fixed-dim": "#e4beb2",
                    "surface": "#f8faf8",
                    "surface-variant": "#e1e3e1",
                    "status-error": "#D32F2F",
                    "surface-bright": "#f8faf8",
                    "on-tertiary-fixed": "#2b160f",
                    "surface-dim": "#d8dad9",
                    "background": "#f8faf8",
                    "tertiary-fixed": "#ffdbce",
                    "on-secondary-fixed-variant": "#643f00",
                    "on-tertiary-fixed-variant": "#5b4137",
                    "on-secondary-container": "#694300",
                    "on-secondary-fixed": "#2a1800",
                    "on-secondary": "#ffffff",
                    "tertiary-container": "#674b41",
                    "secondary-fixed-dim": "#ffb957",
                    "surface-container-lowest": "#ffffff",
                    "on-error": "#ffffff",
                    "secondary-container": "#fcab28",
                    "on-primary-fixed": "#002203",
                    "surface-container-high": "#e6e9e7",
                    "surface-tint": "#2a6b2c",
                    "on-background": "#191c1b",
                    "error-container": "#ffdad6",
                    "secondary-fixed": "#ffddb5",
                    "primary-fixed-dim": "#91d78a",
                    "inverse-on-surface": "#eff1ef",
                    "error": "#ba1a1a",
                    "on-primary": "#ffffff",
                    "on-primary-fixed-variant": "#0c5216",
                    "surface-container": "#eceeec",
                    "on-surface-variant": "#41493e",
                    "outline-variant": "#c0c9bb",
                    "surface-container-low": "#f2f4f2",
                    "surface-container-highest": "#e1e3e1",
                    "outline": "#717a6d",
                    "primary": "#00450d",
                    "on-error-container": "#93000a",
                    "orange-money": "#FF8200",
                    "tertiary": "#4d352b",
                    "status-success": "#2E7D32"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "gutter": "16px",
                    "stack-gap": "12px",
                    "margin-mobile": "16px",
                    "margin-desktop": "32px",
                    "base": "8px",
                    "card-padding": "20px"
            },
            "fontFamily": {
                    "label-sm": ["JetBrains Mono"],
                    "label-caps": ["Hanken Grotesk"],
                    "headline-lg": ["Hanken Grotesk"],
                    "body-md-bold": ["Hanken Grotesk"],
                    "body-md": ["Hanken Grotesk"],
                    "headline-md": ["Hanken Grotesk"],
                    "body-lg": ["Hanken Grotesk"],
                    "headline-lg-mobile": ["Hanken Grotesk"]
            },
            "fontSize": {
                    "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                    "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.1em", "fontWeight": "700"}],
                    "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md-bold": ["16px", {"lineHeight": "24px", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                    "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}]
            }
          }
        }
      }
</script>
<style>
        body { font-family: 'Hanken Grotesk', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 69, 13, 0.05);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 69, 13, 0.85) 0%, rgba(27, 94, 32, 0.6) 100%);
        }
        .carousel-container {
            position: relative;
            width: 100%;
            height: 550px;
            overflow: hidden;
        }
        .carousel-track {
            display: flex;
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }
        .carousel-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>