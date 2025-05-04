<div class="space-y-6 text-sm">
    <div class="p-4 border rounded-lg bg-primary-50 dark:bg-primary-950 border-primary-200 dark:border-primary-800">
        <h3 class="mb-2 text-base font-medium text-primary-900 dark:text-primary-100">
            <x-heroicon-o-light-bulb class="inline-block w-5 h-5 mr-1" />
            Title Separator
        </h3>
        <p class="text-primary-700 dark:text-primary-300">
            The title separator is the character that appears between sections of your page title. Instead of typing the
            separator character directly in your title formats, use the <code>{separator}</code> placeholder.
            This allows you to change the separator site-wide by updating a single setting.
        </p>
        <div class="mt-2 text-primary-700 dark:text-primary-300">
            <strong>Example:</strong><br>
            With the format <code>{page_title} {separator} {site_name}</code>:
            <ul class="mt-1 ml-4 list-disc list-inside">
                <li>If separator is <code>|</code>: <code>About Us | My Website</code></li>
                <li>If separator is <code>-</code>: <code>About Us - My Website</code></li>
                <li>If separator is <code>·</code>: <code>About Us · My Website</code></li>
            </ul>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Core Placeholders (Available in all formats)</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{site_name}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Your website name</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">My Awesome Website</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{separator}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The title separator character
                        you've configured</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">| or - or ·</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Standard Pages</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{page_title}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The title of the specific page
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">About Us</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong> <code>{page_title} {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>About Us | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Blog Posts</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{post_title}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The title of the blog post</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">How to Grow Tomatoes</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{post_category}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The category of the blog post</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Gardening</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{author_name}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The author of the post</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Jane Smith</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{publish_date}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The year the post was published
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">2023</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong>
            <code>{post_title} {separator} {post_category} {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>How to Grow Tomatoes | Gardening | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Product Pages</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{product_name}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The name of the product</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Wireless Headphones</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{product_category}</code>
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The category of the product</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Audio Equipment</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{product_brand}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The brand of the product</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">SoundMax</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{price}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The formatted price of the product
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">$129.99</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong>
            <code>{product_name} {separator} {product_brand} {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>Wireless Headphones | SoundMax | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Category Pages</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{category_name}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The name of the category</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Men's Clothing</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{parent_category}</code>
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The parent category (if
                        applicable)</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Apparel</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{products_count}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The number of products in the
                        category</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">42</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong> <code>{category_name} {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>Men's Clothing | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Search Results</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{search_term}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The search query entered by the
                        user</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">organic fertilizer</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{results_count}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The number of search results</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">24</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong> <code>Search results for "{search_term}" {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>Search results for "organic fertilizer" | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Author Pages</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Placeholder
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{author_name}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The name of the author</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">John Doe</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700"><code>{post_count}</code></td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The number of posts by this author
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">15</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2">
            <strong>Example format:</strong> <code>Posts by {author_name} {separator} {site_name}</code><br>
            <strong>Result:</strong> <code>Posts by John Doe | My Awesome Website</code>
        </div>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Open Graph (Social Media) Tags</h3>
        <p class="mb-3">Open Graph meta tags control how your content appears when shared on social media platforms like
            Facebook.</p>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Property
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Your Setting
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">og:type</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The type of content (website,
                        article, product, etc.)</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">website</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">og:title</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The title that appears when shared
                        (use {page_title})</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{page_title}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">og:description</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The description that appears (use
                        {meta_description})</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{meta_description}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">og:image</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The image that appears with the
                        share</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Your uploaded image</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Twitter Cards</h3>
        <p class="mb-3">Twitter Cards control how your content appears when shared on Twitter.</p>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Property
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Options
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">twitter:card</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The type of card to show</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">summary, summary_large_image, app,
                        player</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">twitter:site</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Your website's Twitter handle</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">@yourtwitter</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">twitter:title</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The title that appears in the
                        Twitter Card</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{page_title}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">twitter:description</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The description that appears</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{meta_description}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h3 class="mb-2 text-base font-medium">Schema.org Structured Data</h3>
        <p class="mb-3">Schema.org markup helps search engines understand your content better, potentially leading to
            rich results.</p>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Property
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description
                    </th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Options
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">@type</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The type of entity your site
                        represents</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Organization, Person,
                        LocalBusiness, etc.</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">name</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The name of your organization/site
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{site_name}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">description</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">A description of your
                        organization/site</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">{meta_description}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">logo</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">The logo of your organization/site
                    </td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Your uploaded logo image</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="mb-2 text-base font-medium">SEO Best Practices for Title Formats</h3>
        <ol class="list-decimal list-inside">
            <li class="mb-1">Keep titles under 60-65 characters to avoid truncation in search results</li>
            <li class="mb-1">Place important keywords near the beginning of the title</li>
            <li class="mb-1">Be descriptive yet concise to improve click-through rates</li>
            <li class="mb-1">Include your brand name to build recognition, usually at the end</li>
            <li class="mb-1">Be consistent with your format structure across similar page types</li>
        </ol>
    </div>

    <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50 dark:bg-yellow-950 dark:border-yellow-800">
        <h3 class="mb-2 text-base font-medium text-yellow-900 dark:text-yellow-100">
            <x-heroicon-o-exclamation-triangle class="inline-block w-5 h-5 mr-1" />
            Meta Description Recommendations
        </h3>
        <ul class="mt-1 space-y-1 text-yellow-700 list-disc list-inside dark:text-yellow-300">
            <li>Keep meta descriptions between 150-160 characters</li>
            <li>Include your primary keyword naturally near the beginning</li>
            <li>Write compelling copy that entices users to click</li>
            <li>Include a call-to-action when appropriate</li>
            <li>Make each description unique for every page</li>
        </ul>
    </div>

    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-950 dark:border-blue-800">
        <h3 class="mb-2 text-base font-medium text-blue-900 dark:text-blue-100">
            <x-heroicon-o-information-circle class="inline-block w-5 h-5 mr-1" />
            Robots.txt and Sitemap Tips
        </h3>
        <p class="mb-2 text-blue-700 dark:text-blue-300">
            Your robots.txt file controls which parts of your site search engines can access, while your sitemap helps
            them discover and understand your content structure.
        </p>
        <div class="text-blue-700 dark:text-blue-300">
            <strong>Common robots.txt directives:</strong>
            <ul class="mt-1 ml-4 list-disc list-inside">
                <li><code>User-agent: *</code> - Applies to all search engines</li>
                <li><code>Allow: /</code> - Allow indexing of all pages</li>
                <li><code>Disallow: /admin/</code> - Block indexing of admin pages</li>
                <li><code>Disallow: /private/</code> - Block indexing of private content</li>
            </ul>
        </div>
        <div class="mt-2 text-blue-700 dark:text-blue-300">
            <strong>Sitemap best practices:</strong>
            <ul class="mt-1 ml-4 list-disc list-inside">
                <li>Include all important pages (but exclude admin, search, login)</li>
                <li>Use the <code>&lt;priority&gt;</code> tag to indicate relative importance (home page = 1.0)</li>
                <li>Use the <code>&lt;changefreq&gt;</code> tag to indicate update frequency</li>
                <li>Keep your sitemap updated whenever content changes</li>
                <li>Submit your sitemap to search engines via their webmaster tools</li>
            </ul>
        </div>
    </div>

    <div class="p-4 border border-green-200 rounded-lg bg-green-50 dark:bg-green-950 dark:border-green-800">
        <h3 class="mb-2 text-base font-medium text-green-900 dark:text-green-100">
            <x-heroicon-o-check-circle class="inline-block w-5 h-5 mr-1" />
            Social Media Image Recommendations
        </h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <h4 class="mb-1 font-medium">Open Graph Images</h4>
                <ul class="text-green-700 list-disc list-inside dark:text-green-300">
                    <li>Optimal size: 1200 × 630 pixels</li>
                    <li>Minimum size: 600 × 315 pixels</li>
                    <li>Aspect ratio: 1.91:1</li>
                    <li>File format: JPG or PNG</li>
                    <li>File size: Less than 8MB</li>
                </ul>
            </div>
            <div>
                <h4 class="mb-1 font-medium">Twitter Card Images</h4>
                <ul class="text-green-700 list-disc list-inside dark:text-green-300">
                    <li>Summary card: 800 × 418 pixels</li>
                    <li>Summary with large image: 800 × 418 pixels</li>
                    <li>Aspect ratio: 1.91:1</li>
                    <li>File format: JPG, PNG, or GIF</li>
                    <li>File size: Less than 5MB</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="p-4 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-950 dark:border-purple-800">
        <h3 class="mb-2 text-base font-medium text-purple-900 dark:text-purple-100">
            <x-heroicon-o-sparkles class="inline-block w-5 h-5 mr-1" />
            Schema.org Types and Use Cases
        </h3>
        <div class="text-purple-700 dark:text-purple-300">
            <p class="mb-2">Different Schema.org types are appropriate for different kinds of websites:</p>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th
                            class="px-3 py-2 text-left bg-purple-100 border border-purple-200 dark:border-purple-700 dark:bg-purple-900">
                            Type</th>
                        <th
                            class="px-3 py-2 text-left bg-purple-100 border border-purple-200 dark:border-purple-700 dark:bg-purple-900">
                            Use Case</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700"><code>Organization</code>
                        </td>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700">Business websites,
                            corporate sites, non-profits</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700"><code>Person</code></td>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700">Personal websites,
                            portfolios, resumes</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700"><code>LocalBusiness</code>
                        </td>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700">Stores, restaurants,
                            services with physical locations</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700"><code>WebSite</code></td>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700">Used alongside other types
                            to provide site-specific info</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700"><code>Product</code></td>
                        <td class="px-3 py-2 border border-purple-200 dark:border-purple-700">E-commerce sites, product
                            pages</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-4 border border-red-200 rounded-lg bg-red-50 dark:bg-red-950 dark:border-red-800">
        <h3 class="mb-2 text-base font-medium text-red-900 dark:text-red-100">
            <x-heroicon-o-flag class="inline-block w-5 h-5 mr-1" />
            Common SEO Mistakes to Avoid
        </h3>
        <ul class="text-red-700 list-disc list-inside dark:text-red-300">
            <li>Using the same title and description on multiple pages</li>
            <li>Creating overly long titles that get truncated in search results</li>
            <li>Using generic descriptions that don't compel users to click</li>
            <li>Forgetting to include canonical URLs for duplicate content</li>
            <li>Neglecting to update metadata when content changes</li>
            <li>Using incorrect or irrelevant schema.org markup</li>
            <li>Including important content only in images without proper alt text</li>
            <li>Blocking search engines from crawling important pages</li>
        </ul>
    </div>

    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="mb-2 text-base font-medium">Verification Codes Guide</h3>
        <p class="mb-3">Verification codes prove to search engines that you own your website and give you access to
            their webmaster tools.</p>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left bg-gray-100 border border-gray-200 dark:border-gray-700 dark:bg-gray-700">
                        Search Engine</th>
                    <th
                        class="px-3 py-2 text-left bg-gray-100 border border-gray-200 dark:border-gray-700 dark:bg-gray-700">
                        Verification Method</th>
                    <th
                        class="px-3 py-2 text-left bg-gray-100 border border-gray-200 dark:border-gray-700 dark:bg-gray-700">
                        Where to Get It</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Google</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Meta tag content value</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Google Search Console</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Bing</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Meta tag content value</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Bing Webmaster Tools</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Yandex</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Meta tag content value</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Yandex Webmaster</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Baidu</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Meta tag content value</td>
                    <td class="px-3 py-2 border border-gray-200 dark:border-gray-700">Baidu Webmaster Tools</td>
                </tr>
            </tbody>
        </table>
        <p class="mt-2">Enter only the content value of the meta tag, not the full HTML tag.</p>
    </div>
</div>
