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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
                        Placeholder</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Description</th>
                    <th
                        class="px-3 py-2 text-left border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        Example</th>
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
</div>
