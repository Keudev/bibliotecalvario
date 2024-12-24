<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Books API Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .book-info {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Search Book by ISBN</h1>
    <input type="text" id="isbn" placeholder="Enter ISBN">
    <button id="search">Search</button>
    
    <div class="book-info" id="book-info"></div>

    <script>
        $(document).ready(function() {
            $('#search').on('click', function() {
                var isbn = $('#isbn').val();
                if (isbn) {
                    $.ajax({
                        url: 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn,
                        method: 'GET',
                        success: function(data) {
                            if (data.items) {
                                var book = data.items[0].volumeInfo;
                                var title = book.title || 'N/A';
                                var authors = book.authors ? book.authors.join(', ') : 'N/A';
                                var publisher = book.publisher || 'N/A';
                                var publishedDate = book.publishedDate || 'N/A';
                                var description = book.description || 'N/A';
                                var thumbnail = book.imageLinks ? book.imageLinks.thumbnail : '';

                                var bookInfoHtml = `
                                    <h2>${title}</h2>
                                    <p><strong>Authors:</strong> ${authors}</p>
                                    <p><strong>Publisher:</strong> ${publisher}</p>
                                    <p><strong>Published Date:</strong> ${publishedDate}</p>
                                    <p><strong>Description:</strong> ${description}</p>
                                    ${thumbnail ? `<img src="${thumbnail}" alt="Book Cover">` : ''}
                                `;
                                $('#book-info').html(bookInfoHtml);
                            } else {
                                $('#book-info').html('<p>No book found for the provided ISBN.</p>');
                            }
                        },
                        error: function() {
                            $('#book-info').html('<p>Error occurred while fetching book data.</p>');
                        }
                    });
                } else {
                    $('#book-info').html('<p>Please enter an ISBN.</p>');
                }
            });
        });
    </script>
</body>
</html>
