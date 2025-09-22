document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('file-input');
  const fileNameDisplay = document.getElementById('file-name');
  const fileProgressDisplay = document.getElementById('file-progress');
  const progressBar = document.getElementById('progress-bar');
  const progressSection = document.getElementById('upload-progress-section');

  // Initially hide the progress bar section
  progressSection.style.display = 'none';

  fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      fileNameDisplay.textContent = `Uploading ${file.name}...`;
      progressSection.style.display = 'block';
    }
  });

  window.simulateUpload = () => {
    let progress = 0;
    const interval = setInterval(() => {
      if (progress >= 100) {
        clearInterval(interval);
        // You would typically redirect the user or show a success message here
        fileNameDisplay.textContent = 'Upload complete!';
        fileProgressDisplay.textContent = '100%';
      } else {
        progress += 10;
        progressBar.style.width = `${progress}%`;
        fileProgressDisplay.textContent = `${progress}%`;
      }
    }, 200);
  };
});