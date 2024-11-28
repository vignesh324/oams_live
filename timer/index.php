<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Sale System</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .live {
            background-color: red;
            color: white;
        }

        .pending {
            background-color: grey;
            color: white;
        }

        .completed {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sale System</h1>
        <p id="datetime"></p>
        <p id="session-time">Total Session Time: 10:00</p>
        <p id="batch-time">Batch Time: 01:00</p>
        <table id="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be populated dynamically -->
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productTableBody = document.getElementById("product-table").getElementsByTagName("tbody")[0];
            const sessionTimeElement = document.getElementById("session-time");
            const batchTimeElement = document.getElementById("batch-time");
            const dateTimeElement = document.getElementById("datetime");

            let batchIndex = 0;
            let products = [];
            let serverTimeOffset = 0;

            function fetchProducts() {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            products = response.products;
                            const serverTime = response.serverTime;
                            const clientTime = Math.floor(Date.now() / 1000);
                            serverTimeOffset = clientTime - serverTime;

                            updateProductStatuses();
                            renderProducts();
                        } else {
                            console.error('Failed to fetch products');
                        }
                    }
                };
                xhr.open("GET", "backend.php", true);
                xhr.send();
            }

            function updateProductStatuses() {
                products.forEach((product, index) => {
                    if (index >= batchIndex * 5 && index < (batchIndex + 1) * 5) {
                        product.status = "Live";
                    } else if (index < batchIndex * 5) {
                        product.status = "Completed";
                    } else {
                        product.status = "Pending";
                    }
                });

                // Sort products: Live first, then Pending, then Completed
                products.sort((a, b) => {
                    const statusOrder = { "Live": 1, "Pending": 2, "Completed": 3 };
                    return statusOrder[a.status] - statusOrder[b.status];
                });
            }

            function renderProducts() {
                productTableBody.innerHTML = "";
                products.forEach(product => {
                    const row = document.createElement("tr");
                    row.className = product.status.toLowerCase(); // Add class based on status
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.price}</td>
                        <td id="status-${product.id}">${product.status}</td>
                        <td><button onclick="buyProduct(${product.id})" ${product.status !== 'Live' ? 'disabled' : ''}>Buy</button></td>
                    `;
                    productTableBody.appendChild(row);
                });
            }

            function updateDateTime() {
                const now = new Date();
                dateTimeElement.textContent = now.toLocaleString();
            }

            function buyProduct(productId) {
                console.log(`Product ${productId} bought`);
                // Placeholder for updating the status after buying
            }

            fetchProducts();
            updateDateTime();

            // Update product statuses every 5 seconds
            setInterval(fetchProducts, 5000);

            // Set session timer (10 minutes)
            let sessionTimer = 10 * 60;
            const sessionInterval = setInterval(() => {
                const minutes = Math.floor(sessionTimer / 60);
                const seconds = sessionTimer % 60;
                sessionTimeElement.textContent = `Total Session Time: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                sessionTimer--;
                if (sessionTimer < 0) {
                    clearInterval(sessionInterval);
                    sessionTimeElement.textContent = "Total Session Ended";
                }
            }, 1000);

            // Set batch timer (1 minute)
            let batchTimer = 1 * 60;
            const batchInterval = setInterval(() => {
                const minutes = Math.floor(batchTimer / 60);
                const seconds = batchTimer % 60;
                batchTimeElement.textContent = `Batch Time: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                batchTimer--;
                if (batchTimer < 0) {
                    batchIndex++;
                    if (batchIndex >= 10) {
                        clearInterval(batchInterval);
                        batchTimeElement.textContent = "All Batches Completed";
                    } else {
                        batchTimer = 1 * 60;
                        updateProductStatuses();
                        renderProducts();
                    }
                }
            }, 1000);
        });

        function buyProduct(productId) {
            console.log(`Product ${productId} bought`);
            // Placeholder for updating the status after buying
        }
    </script>
</body>
</html>
