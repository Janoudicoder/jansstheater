function applyImageGrid(containerId) {
    const container = document.getElementById(containerId);
    const images = container.querySelectorAll('img');
  
    // Apply alternating sizes dynamically
    images.forEach((img, index) => {
      if (index % 2 === 0) {
        img.style.width = '370px';
        img.style.height = '270px';
      } else {
        img.style.width = '270px';
        img.style.height = '300px';
      }
      img.style.objectFit = 'cover'; // Prevent distortion
    });
  
    // Ensure the grid layout
    container.style.display = 'grid';
    container.style.gridTemplateColumns = 'repeat(2, 1fr)'; // Two columns layout
    container.style.gap = '10px'; // Space between images
    container.style.justifyContent = 'start'; // Align to the left
  }
  