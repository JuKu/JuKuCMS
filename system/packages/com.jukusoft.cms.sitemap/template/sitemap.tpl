<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    {foreach $urls url}
        <url>
            <loc>{$url.loc}</loc>
            {* <lastmod>2018-03-24T12:14:13+00:00</lastmod> *}
            <lastmod>{$url.lastmod}</lastmod>
            <changefreq>{$url.changefreq}</changefreq>
            <priority>{$url.priority}</priority>
        </url>
    {/foreach}
</urlset>