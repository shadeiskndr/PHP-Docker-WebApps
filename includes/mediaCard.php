<?php
/**
 * Renders a media card component
 * 
 * @param array $file The file data from the database
 * @param bool $showCopyButton Whether to show the copy URL button (default: true)
 * @param string $cardSize Size variant of the card (default: 'normal')
 * @return void Outputs HTML directly
 */
function renderMediaCard($file, $showCopyButton = true, $cardSize = 'normal') {
    $classes = $cardSize === 'small' 
        ? 'border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow transform hover:scale-[1.01] transition-all duration-200'
        : 'bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700 transform hover:scale-[1.03] transition-all duration-200';
    
    echo '<div class="' . $classes . '">';
    
    // Media display (image or video)
    if (strpos($file['file_type'], 'image/') === 0) {
        echo '<div class="relative aspect-video bg-gray-200 dark:bg-gray-700">';
        echo '<img src="' . htmlspecialchars($file['url']) . '" alt="' . htmlspecialchars($file['original_filename']) . '" class="absolute inset-0 w-full h-full object-cover">';
        echo '</div>';
    } else if (strpos($file['file_type'], 'video/') === 0) {
        echo '<div class="relative aspect-video bg-gray-200 dark:bg-gray-700">';
        echo '<video controls class="absolute inset-0 w-full h-full object-cover">
                <source src="' . htmlspecialchars($file['url']) . '" type="' . htmlspecialchars($file['file_type']) . '">
                Your browser does not support the video tag.
              </video>';
        echo '</div>';
    }
    
    // Media info and actions
    echo '<div class="p-4">';
    echo '<h3 class="font-medium text-gray-800 dark:text-gray-200 truncate">' . htmlspecialchars($file['original_filename']) . '</h3>';
    echo '<div class="mt-2 flex justify-between items-center">';
    echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . date('F j, Y', strtotime($file['upload_date'])) . '</span>';
    echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . round($file['file_size'] / 1024, 2) . ' KB</span>';
    echo '</div>';
    echo '<div class="mt-3 flex space-x-2">';
    echo '<a href="' . htmlspecialchars($file['url']) . '" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200">View Full Size</a>';
    
    if ($showCopyButton) {
        echo '<button class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200" onclick="copyToClipboard(\'' . htmlspecialchars($file['url']) . '\')">Copy URL</button>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
