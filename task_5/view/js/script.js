// API Configuration
const API_BASE_URL = 'https://your-api-domain.com/api';
const ENDPOINTS = {
    SALES_OVERVIEW: '/dashboard/sales-overview',
    INVENTORY_SUMMARY: '/dashboard/inventory-summary',
    PURCHASE_OVERVIEW: '/dashboard/purchase-overview',
    PRODUCT_SUMMARY: '/dashboard/product-summary',
    SALES_PURCHASE_CHART: '/dashboard/sales-purchase-chart',
    SALES_SUMMARY_CHART: '/dashboard/sales-summary-chart',
    TOP_SELLING_STOCK: '/dashboard/top-selling-stock',
    LOW_QUANTITY_STOCK: '/dashboard/low-quantity-stock'
};

// Authentication token (replace with your actual auth token retrieval)
const getAuthToken = () => {
    return localStorage.getItem('authToken') || '';
};

async function fetchData(endpoint) {
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error(`Error fetching data from ${endpoint}:`, error);
        return null;
    }
}

// Update Sales Overview Card
async function updateSalesOverview() {
    const data = await fetchData(ENDPOINTS.SALES_OVERVIEW);
    if (data) {
        const card = document.querySelector('.card:nth-child(1) .metrics');
        if (card) {
            card.innerHTML = `
                <div><img src="assets/icons/Sales.png" alt="Sales Icon" class="icon"><strong>${data.totalSales}৳</strong> Sales</div>
                <div><img src="assets/icons/Revenue.png" alt="Revenue Icon" class="icon"><strong>${data.revenue}৳</strong> Revenue</div>
                <div><img src="assets/icons/Profit.png" alt="Profit Icon" class="icon"><strong>${data.profit}৳</strong> Profit</div>
                <div><img src="assets/icons/Cost.png" alt="Cost Icon" class="icon"><strong>${data.cost}৳</strong> Cost</div>
            `;
        }
    }
}

// Update Inventory Summary Card
async function updateInventorySummary() {
    const data = await fetchData(ENDPOINTS.INVENTORY_SUMMARY);
    if (data) {
        const card = document.querySelector('.card:nth-child(2)');
        if (card) {
            card.innerHTML = `
                <h4>Inventory Summary</h4>
                <div><img src="assets/icons/Quantity.png" alt="Quantity icon"><strong>${data.quantityInHand}</strong> Quantity in Hand</div>
                <div><img src="assets/icons/On the way.png" alt="On the way icon"><strong>${data.toBeReceived}</strong> To be received</div>
            `;
        }
    }
}

// Update Purchase Overview Card
async function updatePurchaseOverview() {
    const data = await fetchData(ENDPOINTS.PURCHASE_OVERVIEW);
    if (data) {
        const card = document.querySelector('.card:nth-child(3) .metrics');
        if (card) {
            card.innerHTML = `
                <div><img src="assets/icons/Purchase.png" alt="Purchase icon"><strong>${data.totalPurchases}</strong> Purchase</div>
                <div><img src="assets/icons/Cost.png" alt="Cost icon"><strong>${data.totalCost}৳</strong> Cost</div>
                <div><img src="assets/icons/Cancel.png" alt="Cancel icon"><strong>${data.canceledPurchases}</strong> Cancel</div>
                <div><img src="assets/icons/Profit.png" alt="Profit icon"><strong>${data.returnedAmount}৳</strong> Return</div>
            `;
        }
    }
}

// Update Product Summary Card
async function updateProductSummary() {
    const data = await fetchData(ENDPOINTS.PRODUCT_SUMMARY);
    if (data) {
        const card = document.querySelector('.card:nth-child(4)');
        if (card) {
            card.innerHTML = `
                <h4>Product Summary</h4>
                <div><img src="assets/icons/Suppliers.png" alt="Suppliers icon"><strong>${data.returnedProducts}</strong> Return Product</div>
                <div><img src="assets/icons/Categories.png" alt="Categories icon"><strong>${data.categoriesCount}</strong> Number of Categories</div>
            `;
        }
    }
}

// Initialize Charts with API Data
async function initializeCharts() {
    // Bar Chart (Sales & Purchase)
    const barChartData = await fetchData(ENDPOINTS.SALES_PURCHASE_CHART);
    if (barChartData) {
        const ctxBar = document.getElementById("barChart").getContext("2d");
        new Chart(ctxBar, {
            type: "bar",
            data: {
                labels: barChartData.labels,
                datasets: [{
                        label: "Purchase",
                        data: barChartData.purchaseData,
                        backgroundColor: "#4a90e2"
                    },
                    {
                        label: "Sales",
                        data: barChartData.salesData,
                        backgroundColor: "#7ed957"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Line Chart (Sales Summary)
    const lineChartData = await fetchData(ENDPOINTS.SALES_SUMMARY_CHART);
    if (lineChartData) {
        const ctxLine = document.getElementById("lineChart").getContext("2d");
        new Chart(ctxLine, {
            type: "line",
            data: {
                labels: lineChartData.labels,
                datasets: [{
                        label: "Revenue",
                        data: lineChartData.revenueData,
                        borderColor: "#4a90e2",
                        fill: false
                    },
                    {
                        label: "Profit",
                        data: lineChartData.profitData,
                        borderColor: "#7ed957",
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

// Update Top Selling Stock Table
async function updateTopSellingStock() {
    const data = await fetchData(ENDPOINTS.TOP_SELLING_STOCK);
    if (data && data.length > 0) {
        const table = document.querySelector('.stock-box:nth-child(1) table');
        if (table) {
            // Clear existing rows except header
            while (table.rows.length > 1) {
                table.deleteRow(1);
            }

            // Add new rows
            data.forEach(item => {
                const row = table.insertRow();
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.sold}</td>
                    <td>${item.remaining}</td>
                    <td>${item.price}৳</td>
                `;
            });
        }
    }
}

// Update Low Quantity Stock List
async function updateLowQuantityStock() {
    const data = await fetchData(ENDPOINTS.LOW_QUANTITY_STOCK);
    if (data && data.length > 0) {
        const list = document.querySelector('.stock-box:nth-child(2) ul');
        if (list) {
            list.innerHTML = '';
            data.forEach(item => {
                const li = document.createElement('li');
                li.innerHTML = `
                    ${item.icon} ${item.name} – ${item.quantity} pcs 
                    ${item.isCritical ? '<span class="low">Low</span>' : ''}
                `;
                list.appendChild(li);
            });
        }
    }
}

// Initialize all dashboard components
async function initializeDashboard() {
    try {
        // Load all data in parallel
        await Promise.all([
            updateSalesOverview(),
            updateInventorySummary(),
            updatePurchaseOverview(),
            updateProductSummary(),
            initializeCharts(),
            updateTopSellingStock(),
            updateLowQuantityStock()
        ]);
    } catch (error) {
        console.error('Error initializing dashboard:', error);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', initializeDashboard);