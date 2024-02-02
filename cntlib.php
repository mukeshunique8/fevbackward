<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["userDetails"])) {
  // Redirect to login page or display an error
  header("Location: login.php");
  exit();
}

// Access user details
$userDetails = $_SESSION["userDetails"];
$employeeID = $userDetails["employeeID"];
$pseudoname = $userDetails["pseudoname"];
$mailID = $userDetails["mailID"];
$tutorname = $userDetails["tutorname"];
$profile_image = $userDetails["profile_image"];
// Access more fields as needed
?>
<?php
// Your PHP code
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CNT</title>
  <link rel="stylesheet" href="cntlib.css">

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


</head>

<body>
  <div class="container">
    <div class="userdetails">
      <div class="welcomeHeader">
      <h1>Welcome, <?php echo $pseudoname; ?>!</h1>
      <ion-icon name="menu-outline" id="userdetailsToggle"></ion-icon>
      <!-- <ion-icon name="add-outline" id="userdetailsToggle"></ion-icon> -->
      </div>
      <div class="userprofile" id="userprofile">

        <div class="profile1">
        <p> EMPLOYEE ID : <span class="highlight"><?php echo $employeeID; ?></span></p>
          <p> Pseudoname : <span class="highlight"><?php echo $pseudoname; ?></span></p>
          <p> Tutorname : <span class="highlight"><?php echo $tutorname; ?></span></p>
          <p> MAIL ID : <span class="highlight"><?php echo $mailID; ?></span></p>


          <p  id="backwardPlanningCount"> <em> Backward Planning Count :</em> </p>
          <div id="likescountdiv">

            <!-- <p  id="docslikesCount" class="heart"></p> -->
            <ion-icon name="heart-outline"></ion-icon>
            <span id="docslikesCount" ></span>
          </div>
          

        </div>
        <div class="profile2">

        <img src="<?php echo $profile_image; ?>" alt="Image" id="profileimage">
        

        </div>

      </div>
    </div>

    <!-- Add an ID to the search input for easy access -->
    <div class="search">
      <input type="text" id="search" placeholder="Search by Topic">
      <button id="searchButton">Search</button>
      
    </div>

    
    
    <div id="topicSuggestions"></div> 
    <!-- Display the search results -->
    <div id="searchResults"></div>



    <div class="uploadcontainer">
      <button id="uploadButton">Upload</button>
      <!-- Modal HTML -->
      <div id="uploadModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">

          <div class="grade">
          <label for="grade">Grade:</label>
            <select name="grade" id="grade" required>
                <option value="Grade 1">1</option>
                <option value="Grade 2">2</option>
                <option value="Grade 3">3</option>
                <option value="Grade 4">4</option>
                <option value="Grade 5">5</option>
                <option value="Grade 6">6</option>
                <option value="Grade 7">7</option>
                <option value="Grade 8">8</option>
               <option value="Grade HS">HS</option>
                <!-- Add more options as needed -->
            </select>
            </div>
            <input type="text" placeholder="Topic Name" name="topic" id="topic" required  onfocus="changePlaceholder()">
        
            <input type="file" name="file" id="file" required>
            <button type="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ... Your HTML and CSS ... -->
 <!-- ... Your HTML and CSS ... -->
 <script>
  function changePlaceholder() {
        document.getElementById('topic').placeholder = 'Kindly paste the topic here';
    }

document.getElementById('topic').addEventListener('input', function (event) {
      // Prevent direct typing
      this.value = '';
    });

    document.getElementById('topic').addEventListener('paste', function (event) {
      // Allow pasting
      const pastedText = (event.clipboardData || window.clipboardData).getData('text');
      this.value = pastedText;
      event.preventDefault();
    });

    document.addEventListener('DOMContentLoaded', function() {
  const userdetailsToggle = document.getElementById('userdetailsToggle');
  const userprofile = document.getElementById('userprofile');
  const searchButton = document.getElementById('searchButton');
  const searchInput = document.getElementById('search');
  const searchResultsContainer = document.getElementById('searchResults');
  const backwardPlanningCountElement = document.getElementById('backwardPlanningCount');
  const docslikesCountElement = document.getElementById('docslikesCount');
  const topicSuggestionsContainer = document.getElementById('topicSuggestions');


    // Use a boolean variable to track if the animation has been triggered
    let isFirstTimeAnimation = true;


  userdetailsToggle.addEventListener('click', function() {
    userprofile.classList.toggle('open');
  });

  // JavaScript to handle modal opening and closing
  document.getElementById('uploadButton').addEventListener('click', function() {
    document.getElementById('uploadModal').style.display = 'flex';
  });

  function closeModal() {
    document.getElementById('uploadModal').style.display = 'none';
  }

  // Define closeModal globally
  window.closeModal = closeModal;

  document.getElementById('uploadForm').addEventListener('submit', function(event) {
    // Validate form inputs
    const grade = document.getElementById('grade').value.trim();
    const topic = document.getElementById('topic').value.trim();
    const fileInput = document.getElementById('file');

    if (!grade || !topic || !fileInput.files.length) {
      alert('Please fill in all required fields.');
      event.preventDefault(); // Prevent form submission
    } else {
      // Show an alert (you can customize this based on your requirements)
      alert('Form submitted successfully!');
      // Close the modal after successful submission
      closeModal();
    }
  });


  
// Add an event listener to the search input for real-time suggestions
searchInput.addEventListener('input', function() {
    const partialInput = searchInput.value.trim();

    // Check if the input is not empty before making the request
    if (partialInput !== "") {
      // Send an AJAX request to get topic suggestions
      fetch('get_topics.php?partialInput=' + encodeURIComponent(partialInput))
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
          }
          return response.json();
        })
        .then(data => {
          // Display topic suggestions
          renderTopicSuggestions(data);
        })
        .catch(error => {
          console.error('Error fetching topic suggestions:', error);
          // Handle the error as needed
        });
    } else {
      // Clear suggestions if the input is empty
      topicSuggestionsContainer.innerHTML = '';
    }
  });

  function renderTopicSuggestions(suggestions) {
    // Clear previous suggestions
    topicSuggestionsContainer.innerHTML = '';

    if (suggestions && suggestions.length > 0) {
      // Display each suggestion
      suggestions.forEach(suggestion => {
        const suggestionElement = document.createElement('div');
        suggestionElement.classList.add('suggestion');
        suggestionElement.textContent = suggestion;
        suggestionElement.addEventListener('click', function() {
          // Set the selected suggestion as the search input value
          searchInput.value = suggestion;
          // Trigger the search button click event (you can adjust this based on your UI)
          searchButton.click();
          // Clear the suggestions container
          topicSuggestionsContainer.innerHTML = '';
          // Close the userprofile (if it's open)
          userprofile.classList.remove('open');

          
        });
        topicSuggestionsContainer.appendChild(suggestionElement);
      });

      // Check if it's the first time showing suggestions
      if (isFirstTimeAnimation) {
        // Add the animate class to trigger the animation
        topicSuggestionsContainer.classList.add('animate');

        // Remove the animate class after a delay (adjust the delay as needed)
        setTimeout(() => {
          topicSuggestionsContainer.classList.remove('animate');
        }, 2000); // 2000 milliseconds (2 seconds) in this example

        // Set the variable to false to prevent subsequent animations
        isFirstTimeAnimation = false;
      }


      
    }
  }

  // Add an event listener to handle the case when the search input is cleared
  searchInput.addEventListener('change', function() {
    const inputValue = searchInput.value.trim();

    // If the search input is empty, clear suggestions
    if (inputValue === "") {
      topicSuggestionsContainer.innerHTML = '';
      searchResultsContainer.innerHTML = ''; 
      isFirstTimeAnimation = true;
   }
  });
  searchButton.addEventListener('click', function() {
    if (userprofile.classList.contains('open')) {
      userprofile.classList.remove('open');
    }
    const searchTerm = searchInput.value.trim();
    if (searchTerm) {
      // Perform search and display results
      performSearch(searchTerm);
    } else {
      alert('Please enter a search term.');
    }
  });

  function performSearch(searchTerm) {
    // You need to implement server-side logic to query the database based on the search term
    // For simplicity, let's assume you have an endpoint like "search.php" that handles the search
    const searchEndpoint = 'search.php';

    // Use fetch or AJAX to send a request to the server
    fetch(searchEndpoint + '?topic=' + searchTerm)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        // Render search results
        renderSearchResults(data);
      })
      .catch(error => {
        console.error('Error during search:', error);
        alert('Error during search. Please try again.');
      });
  }
  function renderSearchResults(results) {
    // Sort the results by likes count in descending order
    results.sort((a, b) => b.likes - a.likes);

    // Clear previous results
    searchResultsContainer.innerHTML = '';

    if (results && results.length > 0) {
        // Display each result
        results.forEach(result => {
            const resultElement = document.createElement('div');
            resultElement.classList.add('resultContainer');
            resultElement.innerHTML = `
                <h4>${result.topic}</h4>
                <h4 id="gradecolor">${result.grade}</h4>
                <p>${result.uploadedBy}</p>
                <p>${result.uploadedOn.split(' ')[0]}</p>

                <div id="likescountdiv">

                <button class="likeButton" data-document-id="${result.documentId}" data-like-status="${result.likeStatus}" onclick="handleLikeButtonClick(this)"><ion-icon name="heart-outline"></ion-icon></button>
                <span class="likeCount"> ${result.likes}</span>

                </div>

                <button class="ViewBtn" onclick="viewDocument('${result.filePath}')"><ion-icon name="eye-outline"></ion-icon></button>

                <button class="DownloadBtn" onclick="downloadDocument('${result.filePath}')"><ion-icon name="cloud-download-outline"></ion-icon></button>`;
            searchResultsContainer.appendChild(resultElement);
        });
    } else {
        // Display a message if no results found
        searchResultsContainer.innerHTML = '<p>No documents found.</p>';
    }
}


            

  function getBackwardPlanningCount() {
    fetch('get_backward_count.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        backwardPlanningCountElement.textContent = 'Backward Planning Count: ' + data.userCount;
      })
      .catch(error => {
        console.error('Error fetching backward planning count:', error);
        // Handle the error as needed
      });
  }

  // Call the function to get backward planning count
  getBackwardPlanningCount();


  function getDocsLikeCount() {
  fetch('get_likes_count.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      // docslikesCountElement.innerHTML = `<ion-icon name="heart-outline"></ion-icon> ${data.docslikescount}`;

      docslikesCountElement.textContent =   data.docslikescount;
    })
    .catch(error => {
      console.error('Error fetching docs likes count:', error);
      // Handle the error as needed
    });
}

getDocsLikeCount();


});

function downloadDocument(filePath) {
  // Create a link element
  const link = document.createElement('a');
  // Set the href attribute to the file path
  link.href = filePath;
  // Set the download attribute to trigger a download
  link.download = 'downloaded_file';
  // Simulate a click on the anchor element
  link.click();
}


function viewDocument(filePath) {
    // Use Google Docs Viewer to view .docx files
    window.open('https://docs.google.com/viewer?url=' + encodeURIComponent(filePath), '_blank');
}



function handleLikeButtonClick(button) {
    const documentId = button.dataset.documentId;
    let likeStatus = button.dataset.likeStatus;

    // Traverse the DOM to find the topic associated with the clicked like button
    const topicElement = button.closest('.resultContainer').querySelector('h4');
    const topic = topicElement.textContent.trim();

    // Toggle the like status (liked/unliked)
    likeStatus = (likeStatus === 'liked') ? 'unliked' : 'liked';

    // Debugging: Output the values to console
    console.log('documentId:', documentId);
    console.log('likeStatus:', likeStatus);
    console.log('topic:', topic);

    // Implement AJAX request to update like status on the server
    // Example using fetch API
    fetch('update_like_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Set content type
        },
        body: `documentId=${documentId}&likeStatus=${likeStatus}&topic=${topic}`, // Send data as form-urlencoded
    })
    .then(response => response.json())
    .then(data => {
      console.log('Server Response:', data); // Log the entire response


      // Update the UI based on the server response
    if (data.success) {
        // Update the like count and like status in the UI
        const resultContainer = button.closest('.resultContainer');
        const likeCountElement = resultContainer.querySelector('.likeCount');
        if (data.likes !== undefined) {
            likeCountElement.textContent = `${data.likes}`;
        } else {
            console.error('Server response is missing the "likes" property.');
        }
    } else {
        console.error('Failed to update like status:', data.error);
    }
})
    .catch(error => {
        console.error('Error:', error);
    });
}


// console.log('Session Details:', <?php echo json_encode($_SESSION["userDetails"]); ?>);

  </script>
<!-- ... Your HTML and CSS ... -->




</body>

</html>