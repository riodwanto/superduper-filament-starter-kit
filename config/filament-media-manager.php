<?php

return [
    "model" => [
        "folder" => \TomatoPHP\FilamentMediaManager\Models\Folder::class,
        "media" => \TomatoPHP\FilamentMediaManager\Models\Media::class,
    ],

    "api" => [
        "active" => false,
        "middlewares" => [
            "api",
            "auth:sanctum"
        ],
        "prefix" => "api/media-manager",
        "resources" => [
            "folders" => \TomatoPHP\FilamentMediaManager\Http\Resources\FoldersResource::class,
            "folder" => \TomatoPHP\FilamentMediaManager\Http\Resources\FolderResource::class,
            "media" => \TomatoPHP\FilamentMediaManager\Http\Resources\MediaResource::class
        ]
    ],

    "user" => [
      'column_name' => 'name', // Change the value if your field in users table is different from "name"
    ],
];
