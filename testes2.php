<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Library API Example</title>
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
                        url: 'https://openlibrary.org/api/books',
                        method: 'GET',
                        data: {
                            bibkeys: 'ISBN:' + isbn,
                            format: 'json',
                            jscmd: 'data'
                        },
                        success: function(data) {
                            var bookKey = 'ISBN:' + isbn;
                            if (data[bookKey]) {
                                var book = data[bookKey];
                                var title = book.title || 'N/A';
                                var authors = book.authors ? book.authors.map(a => a.name).join(', ') : 'N/A';
                                var publisher = book.publishers ? book.publishers.map(p => p.name).join(', ') : 'N/A';
                                var publishedDate = book.publish_date || 'N/A';
                                var cover = book.cover ? book.cover.medium : '';

                                var bookInfoHtml = `
                                    <h2>${title}</h2>
                                    <p><strong>Authors:</strong> ${authors}</p>
                                    <p><strong>Publisher:</strong> ${publisher}</p>
                                    <p><strong>Published Date:</strong> ${publishedDate}</p>
                                    ${cover ? `<img src="${cover}" alt="Book Cover">` : ''}
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
