<?php
$titleBlock = the_field('titel', 'block', $blockId);
$textBlock = the_field('tekst', 'block', $blockId);
$catBlock = the_field('categorie', 'block', $blockId);

$sqlCatCols = $mysqli->prepare("SELECT id FROM siteworkcms WHERE keuze1 = ? and status = 'actief' ORDER BY volgorde ASC") or die($mysqli->error . __LINE__);
$sqlCatCols->bind_param('s', $catBlock);
$sqlCatCols->execute();
$sqlCatCols->store_result();
$sqlCatCols->bind_result($idCatItem);

// Define the ordered icons array
$svgFiles = ['Dj Mixer.svg', 'Bagpipes.svg', 'Saxophone.svg', 'Guitar.svg', 'Trumpet.svg'];
$numRows = $sqlCatCols->num_rows;
?>
<div id="block-<?=$blockId;?>" class="blocken w-full diensten mx-auto relative team-lijst mb-16">
    <div class="w-full md:w-[58.666667%] text-center mb-[55px]">
        <?php if ($titleBlock) { ?>
            <h2 class="text-[38px] text-center pb-10"><?= $titleBlock; ?></h2>
        <?php } ?>
        <?php if ($textBlock) {
            echo '<span class="block">'.$textBlock.'</span>';
        } ?>
    </div>
    <div class="overflow-hidden relative">
        <?php if ($numRows >= 5) { ?>
        <div id="slider-container" class="flex space-x-8">
            <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);

                // Get and remove the first icon from the array
                $currentSVG = array_shift($svgFiles);

                // If no more icons are available, stop the loop
                if (!$currentSVG) {
                    break;
                }

                echo '<a class="relative team diensten-block overflow-hidden flex-shrink-0 w-1/4" href="' . get_link($idCatItem, 'team') . '/">';
                echo '<div class="absolute bottom-0 z-10 flex w-full flex-col gradient justify-between pt-32 p-12 text-white">';
                echo '<img src="/fa/webfonts/' . $currentSVG . '" alt="' . pathinfo($currentSVG, PATHINFO_FILENAME) . '" class=" h-auto w-[40px]">';

                echo '<span class="font-bold text-xl mt-8 mb-4">' . $titleCatItem . '</span>';
                echo '<span style="width: 190px; height: 75px;">';
                echo '<span class="block font-bold text-sm"></span>';
                echo '<span class="text-sm">' . strip_tags(limit_text($textCatItem, 100)) . '</span>';
                echo '</span>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
        <?php } else { ?>
        <div class="flex flex-wrap justify-center space-x-8">
            <?php
            while ($sqlCatCols->fetch()) {
                $titleCatItem = the_field('item2', $idCatItem);
                $textCatItem = the_field('tekst', $idCatItem);

                // Get and remove the first icon from the array
                $currentSVG = array_shift($svgFiles);

                // If no more icons are available, stop the loop
                if (!$currentSVG) {
                    break;
                }

                echo '<a class="relative team diensten-block w-1/4 mb-4" href="' . get_link($idCatItem, 'team') . '/">';
                echo '<div class="flex flex-col items-center text-center p-4">';
                echo '<img src="/fa/webfonts/' . $currentSVG . '" alt="' . pathinfo($currentSVG, PATHINFO_FILENAME) . '" class="w-24 h-24">';

                echo '<span class="font-bold text-xl mt-4">' . $titleCatItem . '</span>';
                echo '<span class="text-sm mt-2">' . strip_tags(limit_text($textCatItem, 100)) . '</span>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php
$sqlCatCols->close();
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Slider Functionality
    const slider = document.getElementById('slider-container');
if (slider) {
    const items = Array.from(slider.children);
    const itemWidth = items[0].offsetWidth + 16; // Include margin (space-x-4 adds 1rem or 16px)
    let index = 0;
    let startX = 0; // Store the starting position for dragging/swiping
    let isDragging = false; // Flag to track if the user is dragging

    // Clone the items for a seamless loop
    const clonedItems = items.map(item => item.cloneNode(true));
    clonedItems.forEach(item => slider.appendChild(item)); // Append cloned items at the end

    // Set the slider to scroll smoothly and continuously
    const startAutoScroll = () => {
        let scrollWidth = index * itemWidth;
        slider.style.transition = 'transform 0.7s ease';
        slider.style.transform = `translateX(-${scrollWidth}px)`;

        index++;
        if (index >= items.length) {
            index = 0; // Restart without jump, so no empty space
        }
    };

    setInterval(startAutoScroll, 2000); // Auto-scroll every 2 seconds

    // Ensure seamless scroll without reset
    slider.addEventListener('transitionend', () => {
        if (index >= items.length) {
            slider.style.transition = 'none'; // Disable transition during reset
            slider.style.transform = 'translateX(0)'; // Reset to start immediately
            index = 0; // Start from the first item again
            setTimeout(() => {
                slider.style.transition = 'transform 0.7s ease'; // Re-enable transition
            });
        }
    });

    // Manual swiping functionality (mouse events)
    slider.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        slider.style.transition = 'none'; // Disable transition during drag
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const moveX = e.pageX - startX;
        slider.style.transform = `translateX(${moveX - index * itemWidth}px)`; // Move based on drag
    });

    slider.addEventListener('mouseup', () => {
        isDragging = false;
        const moveDistance = Math.abs(startX - (slider.offsetLeft + index * itemWidth));
        
        if (moveDistance > itemWidth / 2) {
            if (startX > slider.offsetLeft) {
                index++; // Swipe right
            } else {
                index--; // Swipe left
            }
        }

        // Reset to auto scroll
        slider.style.transition = 'transform 0.7s ease';
        slider.style.transform = `translateX(-${index * itemWidth}px)`;

        if (index >= items.length) {
            index = 0; // Restart to first item
        } else if (index < 0) {
            index = items.length - 1; // Go to the last item
        }
    });

    slider.addEventListener('mouseleave', () => {
        if (isDragging) {
            isDragging = false;
            slider.style.transition = 'transform 0.7s ease';
            slider.style.transform = `translateX(-${index * itemWidth}px)`;
        }
    });

    // Manual swiping functionality (touch events for mobile)
    slider.addEventListener('touchstart', (e) => {
        isDragging = true;
        startX = e.touches[0].pageX;
        slider.style.transition = 'none'; // Disable transition during swipe
    });

    slider.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        const moveX = e.touches[0].pageX - startX;
        slider.style.transform = `translateX(${moveX - index * itemWidth}px)`; // Move based on swipe
    });

    slider.addEventListener('touchend', () => {
        isDragging = false;
        const moveDistance = Math.abs(startX - (slider.offsetLeft + index * itemWidth));
        
        if (moveDistance > itemWidth / 2) {
            if (startX > slider.offsetLeft) {
                index++; // Swipe right
            } else {
                index--; // Swipe left
            }
        }

        // Reset to auto scroll
        slider.style.transition = 'transform 0.7s ease';
        slider.style.transform = `translateX(-${index * itemWidth}px)`;

        if (index >= items.length) {
            index = 0; // Restart to first item
        } else if (index < 0) {
            index = items.length - 1; // Go to the last item
        }
    });
}


    // Apply Random Colors to Teams
    const teams = document.querySelectorAll('.team');

// Definieer de volgorde van kleuren
const colors = ['#3a2b33', '#3f3927', '#FFC20E', '#3a2b33', '#3f3927'];
let colorIndex = 0;

teams.forEach(team => {
    // Selecteer de achtergrondkleur volgens de opgegeven volgorde
    const backgroundColor = colors[colorIndex];
    
    // Bereken de borderkleur (verschillend van de achtergrondkleur)
    const borderColors = colors.filter(color => color !== backgroundColor);
    const borderColor = borderColors[Math.floor(Math.random() * borderColors.length)];
    
    // Stel de stijlen in
    team.style.backgroundColor = backgroundColor;
    team.style.borderRight = `4px solid ${borderColor}`;
    team.style.borderBottom = `4px solid ${borderColor}`;

    // Controleer of de achtergrondkleur #FFC20E is
    if (backgroundColor.toUpperCase() === '#FFC20E') { // Ensure case consistency
        const spans = team.querySelectorAll('span'); // Select all span elements within the team
        spans.forEach(span => {
            span.style.color = '#2A2A2A'; // Change span text color
        });
    }
    
    colorIndex = (colorIndex + 1) % colors.length;
});


});


</script>
