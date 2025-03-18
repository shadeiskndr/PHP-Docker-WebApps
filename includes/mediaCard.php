<?php
/**
 * Render a media card component.
 *
 * @param array  $file            Row from the media_files table
 * @param bool   $showCopyButton  Show “Copy URL” button (default: true)
 * @param string $cardSize        'normal' | 'small'  (for potential grid sizes)
 */
function renderMediaCard(array $file, bool $showCopyButton = true, string $cardSize = 'normal'): void
{
    // ---------------------------------------------------------------------
    // Small helpers / sanitised vars
    // ---------------------------------------------------------------------
    $url          = htmlspecialchars($file['url'] ?? '', ENT_QUOTES);
    $mime         = htmlspecialchars($file['file_type'] ?? '', ENT_QUOTES);
    $filename     = htmlspecialchars($file['original_filename'] ?? '', ENT_QUOTES);
    $uploadDate   = isset($file['upload_date']) ? date('F j, Y', strtotime($file['upload_date'])) : '';
    $fileSizeKB   = isset($file['file_size'])   ? round($file['file_size'] / 1024, 2) : '';
    $isImage      = str_starts_with($mime, 'image/');
    $isVideo      = str_starts_with($mime, 'video/');
    $cardClasses  = $cardSize === 'small'
        ? 'border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transform hover:scale-[1.01] transition-all duration-200'
        : 'bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl border border-gray-200 dark:border-gray-700 transform hover:scale-[1.03] transition-all duration-200';
    // Tiny 1×1 gif placeholder keeps aspect-ratio without downloading
    $blankGif     = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

    echo '<div class="' . $cardClasses . '">';

    /* ------------------------------------------------------------------ */
    /*  Preview block                                                      */
    /* ------------------------------------------------------------------ */
echo '<div class="media-preview relative aspect-video bg-gray-200 dark:bg-gray-700 cursor-pointer" 
           data-url="' . $url . '" 
           data-mime="' . $mime . '">';

    if ($isImage) {
echo '<img data-src="' . $url . '"              // only data-src
             alt="' . $filename . '"
             loading="lazy"
             class="lazy-load absolute inset-0 w-full h-full object-cover">';
    } elseif ($isVideo) {
        echo '<div class="video-wrapper absolute inset-0">';
        echo     '<video preload="none" class="w-full h-full object-cover">';
        echo         '<source src="' . $url . '" type="' . $mime . '">';
        echo     '</video>';
        echo     '<button class="video-play-btn absolute inset-0 flex items-center justify-center 
                                  text-white text-5xl hover:text-gray-300" 
                         onclick="playVideo(this);event.stopPropagation();">
                     <i class="fas fa-play-circle"></i>
                  </button>';
        echo '</div>';
    } else {
        // Non-media fallback
        echo '<div class="absolute inset-0 flex items-center justify-center">';
        echo     '<i class="fas fa-file-alt text-6xl text-gray-400"></i>';
        echo '</div>';
    }
    echo '</div>'; // preview

    /* ------------------------------------------------------------------ */
    /*  Footer with info + actions                                         */
    /* ------------------------------------------------------------------ */
    echo '<div class="p-4">';
    echo     '<h3 class="font-medium text-gray-800 dark:text-gray-200 truncate" title="' . $filename . '">' . $filename . '</h3>';

    echo     '<div class="mt-2 flex justify-between items-center">';
    echo         '<span class="text-sm text-gray-500 dark:text-gray-400">' . $uploadDate . '</span>';
    echo         '<span class="text-sm text-gray-500 dark:text-gray-400">' . $fileSizeKB . ' KB</span>';
    echo     '</div>';

    echo     '<div class="mt-3 flex space-x-2">';
    echo         '<a href="' . $url . '" target="_blank" 
                          class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200">
                      Download
                  </a>';

    if ($showCopyButton) {
    echo '<button type="button"
             class="copy-url-btn inline-block bg-gray-200 dark:bg-gray-700
                    hover:bg-gray-300 dark:hover:bg-gray-600
                    text-gray-800 dark:text-gray-200 font-bold py-1 px-3
                    rounded-md text-sm transition-colors duration-200"
             data-url="' . $url . '">
        Copy URL
      </button>';
    }
    echo     '</div>'; // actions
    echo '</div>';     // footer
    echo '</div>';     // card
}
?>
