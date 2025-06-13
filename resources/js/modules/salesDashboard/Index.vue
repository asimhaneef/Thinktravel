<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 style="font-size: 32px;font-weight: 700;">Sales Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <Calendar 
                    v-model="overallDateRange" 
                    selectionMode="range" 
                    :manualInput="false" 
                    dateFormat="yy-mm-dd"
                    placeholder="Select a Date Range"
                    showIcon
                    @update:modelValue="getDataStats"
                    class="float-end"
                />
                </ol>
            </div>
            </div>
        </div>
    </div>

        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <p>Total Sales</p>
                            <h3>$ {{ totalSales }}</h3>
                            <span class="bg-success pl-2 pr-2" style="border-radius: 5px;"><i class="pi pi-chart-line" style="font-size: 12px;"></i> {{ totalSalesProjectionPercentage }}</span><br>
                            <sub>compared to last {{ comparisonDays }} days</sub>
                        </div>                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <p>Total Margin</p>
                            <h3>$ {{ totalMargin }}</h3>
                            <span class="bg-danger pl-2 pr-2" style="border-radius: 5px;"><i class="pi pi-chart-line" style="font-size: 12px;"></i> {{ totalMarginProjectionPercentage }}</span><br>
                            <sub>compared to last {{ comparisonDays }} days</sub>
                        </div>                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <p>Outstanding Balance</p>
                            <h3>$ {{ outstandingBalance }}</h3>
                            <span class="bg-info pl-2 pr-2" style="border-radius: 5px;"><i class="pi pi-chart-line" style="font-size: 12px;"></i> {{ outstandingBalanceProjectionPercentage }}</span><br>
                            <sub>compared to last {{ comparisonDays }} days</sub>
                        </div>                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <p>Margin Percentage</p>
                            <h3>{{ marginPercentage }}</h3>
                            <span class="bg-warning pl-2 pr-2" style="border-radius: 5px;"><i class="pi pi-chart-line" style="font-size: 12px;"></i> {{marginPercentageProjectionPercentage}}</span><br>
                            <sub>compared to last {{ comparisonDays }} days</sub>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                </div>
            </div>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="mb-5 col-4">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> 
                                Sales Trend
                            </p>
                            <input type="date" v-model="selectedDate" @change="getLineChartCount" class="col-5 float-end mt-1" style="border:1px solid #ccc; border-radius: 5px;">
                        </div>
                        <div class="card-body">
                            <Chart type="line" :data="lineChartData" :options="lineChartOptions" class="h-[20rem]" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-4">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-building"></i> 
                                Top Suppliers
                            </p>
                            <Dropdown
                                v-model="selectedSupplier"
                                :options="suppliers"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Supplier"
                                filter
                                showClear
                                @change="handleSupplierChange"
                                class="w-100 col-5 float-end mt-1"                                    
                            />
                        </div>
                        <div class="card-body">
                            <Chart type="bar" :data="supplierChartData" :options="supplierChartOptions" class="h-[20rem]" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-4">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-user"></i> 
                                Top Agents
                            </p>
                            <Dropdown
                                v-model="selectedAgent"
                                :options="agents"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Agent"
                                filter
                                showClear
                                @change="handleAgentChange"
                                class="w-100 col-5 float-end mt-1"                                    
                            />
                        </div>
                        <div class="card-body">
                            <Chart type="bar" :data="agentChartData" :options="agentChartOptions" class="h-[20rem]" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-dollar"></i> Payment Breakdown</p>
                        </div>
                        <div class="card-body row">
                            <div class="col-9 offset-3">
                                <Chart type="pie" :data="paymentBreakdownData" :options="paymentBreakdownOptions" class="h-[20rem]" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> 
                                Negative Transactions and High Balance</p>
                        </div>
                        <div class="card-body">
                            <DataTable :value="transactions" tableStyle="min-width: 50rem">
                                <Column field="invoice_date" header="Trans. Date"></Column>
                                <Column field="agent" header="Agent">
                                    <template #body="slotProps">
                                        <div class="d-flex align-items-center">
                                            <span>{{ slotProps.data.agent.first_name }} {{ slotProps.data.agent.last_name }}</span>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="supplier" header="Supplier">
                                    <template #body="slotProps">
                                        <div class="d-flex align-items-center">
                                            <span>{{ slotProps.data.supplier.first_name }} {{ slotProps.data.supplier.last_name }}</span>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="total_sale" header="Total Sales"></Column>
                                <Column field="margin" header="Total Margin"></Column>
                            </DataTable>
                        </div>
                    </div>
                </div>                
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-plane"></i> 
                                Payment Breakdown by Region</p>
                        </div>
                        <div class="card-body row">
                            <div class="col-6" v-for="(regionData, index) in regionPaymentData" :key="index">
                                <h4 class="text-center mb-3">{{ regionData.region }}</h4>
                                <Chart type="pie" :data="getChartData(regionData)" :options="pieChartOptions" class="h-[20rem]" />
                                <p class="text-center mt-2 font-bold">Total: ${{ regionData.payment_breakdown?.total?.toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> 
                                Queries Type</p>
                        </div>
                        <div class="card-body">
                            <Chart type="bar" :data="queriesTypeData" :options="queriesTypeOptions" class="h-[20rem]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from "vue";
import Chart from 'primevue/chart'
import { APP_URL } from '../../constants';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction'; // for selectable, draggable

export default {
        components: {
            FullCalendar,
            Chart
        },
    data() {
        // Get first and last day of current month
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        return {
            overallDateRange: [firstDay, lastDay],
            totalSales: 0,
            totalSalesProjectionPercentage: 0,
            totalMargin: 0,
            totalMarginProjectionPercentage: 0,
            outstandingBalance: 0,
            outstandingBalanceProjectionPercentage: 0,
            marginPercentage: 0,
            marginPercentageProjectionPercentage: 0,
            comparisonDays: 0,
            calendarOptions: {
                    plugins: [dayGridPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,dayGridDay' // user can switch between the three
                    },
                    dateClick: this.handleDateClick,
                    eventClick: this.handleEventClick,
                    eventContent: function (info) {
                        return { html: info.event.title }; // Enable HTML rendering for the event title
                    },
                },
            selectedSupplier: null,
            supplierChartData : this.setSupplierChartData(
                {
                    supplier_names: [],
                    total_sums: []
                }
            ),
            supplierChartOptions : this.setSupplierChartOptions(),
            loading: false,
            selectedAgent: null,
            agentChartData: this.setAgentChartData(
                {
                    agent_names: [],
                    total_sums: []
                }
            ),
            agentChartOptions: this.setAgentChartOptions(),
            categories: [],
            agents: [],
            suppliers: [],
            transactions: [],
            queriesTypeData: this.setQueriesTypeData(
                {
                    total_sums: [0, 0, 0]
                }
            ),
            queriesTypeOptions: this.setQueriesTypeOptions(),
            barChartData: null,
            barChartOptions: null,
            lineChartData: this.setLineChartData(
                {
                    times: [],
                    total_sums: []
                }
            ),
            lineChartOptions: this.setLineChartOptions(),
            selectedDate: new Date().toLocaleDateString('en-CA'),
            paymentBreakdownData: this.setPaymentBreakdownData(
            ),
            paymentBreakdownOptions: this.setPaymentBreakdownOptions(),
            regionPaymentData: [],
            pieChartOptions: this.setPieChartOptions(),
            regionPaymentBreakdownOptions: this.setPieChartOptions(),
            APP_URL: APP_URL
        };
    },
    computed: {
        filteredCategories() {
            return this.categories.filter(category => category.posts && category.posts.length > 0);
        }
    },
    methods: {
        // Add these methods to handle chart destruction
        destroyChart(chartRef) {
            if (chartRef && chartRef.value && chartRef.value.destroy) {
                chartRef.value.destroy();
            }
        },
        async checkFirstLogin() {
            try {
                const response = await axios.get('/api/check-first-login');

                if (response.data.redirect) {
                // Redirect the user to the change-password route
                this.$router.push({ path: '/change-password' });
                }
            } catch (error) {
                console.error('Error checking first login:', error);
            }
        },    
        async getSalesStats(dateFrom, dateTo){
            try {
                const response = await axios.get('/api/sales-stats/'+dateFrom+'/'+dateTo);
                this.totalSales = response.data.totalRevenue;
                this.totalMargin = response.data.total_margin;
                this.outstandingBalance = response.data.outstanding_balance;
                this.marginPercentage = response.data.margin_percentage;
                this.comparisonDays = response.data.comparison_days;

                this.totalSalesProjectionPercentage = response.data.projection_revenue;
                this.totalMarginProjectionPercentage = response.data.projection_margin;
                this.outstandingBalanceProjectionPercentage = response.data.projection_balance;
                this.marginPercentageProjectionPercentage = response.data.projection_margin_percentage;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async getTableTransactions(dateFrom, dateTo) {
            try {
                this.loading = true;
                const Response = await axios.get("/api/table-transactions",{
                    params: {
                        rows: 5,
                        page: 1,
                        dateFrom: dateFrom,
                        dateTo: dateTo
                    }
                });
                if (Response) {
                // handle success, e.g., show a success message or redirect
                this.transactions = Response.data.data.map(saleForm => {
                    const totalSale = saleForm.gross_supplier + saleForm.markup;
                    const totalMargin = saleForm.commission + saleForm.markup;
                    const totalReceived = saleForm.customer_mc + saleForm.customer_vi + saleForm.debit + saleForm.cash + saleForm.cheque_draft + saleForm.etransfer + saleForm.past_credit + saleForm.gift_card + saleForm.amex_payment + saleForm.other_payment;

                    const netCost = saleForm.gross_supplier - saleForm.commission;
                    const balanceDuePayment = totalSale - totalReceived - saleForm.customer_card;
                    const gst = saleForm.sale_type == "air_usa" || saleForm.sale_type == "vacation" || saleForm.sale_type == "fee_only" ? ((totalMargin / 1.05) * 0.05) : 0.00;
                    const chequeCost = netCost - (saleForm.customer_card + saleForm.company_emax + saleForm.company_visa + saleForm.company_master) +  saleForm.other_supplier;
                    const cheque = chequeCost > 0 ? chequeCost : 0.00;
                    const commDue = chequeCost > 0 ? 0.00 : chequeCost * -1;
                    const crossCheckCost = saleForm.commission  -  commDue;
                    const crossCheck = crossCheckCost > 0 ? crossCheckCost : 0.00;
                    const marginOfGst = totalMargin - gst;
                    return {
                        ...saleForm,
                        total_sale: totalSale.toFixed(2),
                        margin: totalMargin.toFixed(2),
                        total_recieved: totalReceived.toFixed(2),
                        balance: balanceDuePayment.toFixed(2),
                        balance_due_payment: balanceDuePayment.toFixed(2),
                        total_margin: totalMargin.toFixed(2),
                        net_cost: netCost.toFixed(2),
                        total_due: netCost.toFixed(2),
                        cheque: cheque.toFixed(2),
                        comm_due: commDue.toFixed(2),                        
                        cross_check: crossCheck.toFixed(2),
                        margin_ofgst: marginOfGst.toFixed(2),
                    };
                });
                }
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting Program:", error);
                this.loading = false;
            } finally {
                this.loading = false; // Set loading to false
            }
        },  
        async getQueryTypeCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/saleform-inquiry-type-counts/'+dateFrom+'/'+dateTo);
                    this.queriesTypeData = this.setQueriesTypeData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        setQueriesTypeData(enquiryTypeCounts, chartRef = null) {
            this.destroyChart(chartRef);
            const documentStyle = getComputedStyle(document.documentElement);
            return {
                labels: ['Flight', 'Vacation', 'Cruise'],
                datasets: [
                    {
                        label: 'Queries Count',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: enquiryTypeCounts.total_sums
                    }
                ]
            };
        },
        setQueriesTypeOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--p-text-color');
            const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
            const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

            return {
                maintainAspectRatio: false,
                aspectRatio: 0.8,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColorSecondary,
                            font: {
                                weight: 500
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder,
                            drawBorder: false
                        }
                    }
                }
            };
        }, 
        async getLineChartCount() {
            try {
                const response = await axios.get('/api/saleform-line-chart-counts/'+this.selectedDate);
                    this.lineChartData = this.setLineChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },       
        setLineChartData(chartData, chartRef = null) {
            this.destroyChart(chartRef);
            const documentStyle = getComputedStyle(document.documentElement);

            return {
                labels: chartData.times,
                datasets: [
                    {
                        type: 'line',
                        label: 'Open',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: chartData.total_sums
                    }
                ]
            };
        },
        setLineChartOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--p-text-color');
            const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
            const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

            return {
                maintainAspectRatio: false,
                aspectRatio: 0.8,
                plugins: {
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder
                        }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder
                        }
                    }
                }
            };
        },
        async getPaymentBreakdownData(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/saleform-payment-breakdown-data/'+dateFrom+'/'+dateTo);
                    this.paymentBreakdownData = this.setPaymentBreakdownData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        setPaymentBreakdownData(chartData, chartRef = null) {
            this.destroyChart(chartRef);
            const documentStyle = getComputedStyle(document.body);

            return {
                labels: ['Customer MC','Customer VI','Debit', 'Cash', 'Cheque', 'E-transfer','Past Credit', 'Gift Card', 'Amex', 'Other'],
                datasets: [
                    {
                        data: chartData,
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        hoverBackgroundColor: documentStyle.getPropertyValue('--p-cyan-500'), 
                    }
                ]
            };
        },
        setPaymentBreakdownOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--p-text-color');

            return {
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: true,
                            color: textColor
                        }
                    }
                }
            };
        },
        async getRegionPaymentBreakdownData(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/cruise-region-payment-breakdown/'+dateFrom+'/'+dateTo);
                this.regionPaymentData = response.data;
            } catch (error) {
                console.error('Error fetching payment breakdown:', error);
            }
        },
        getChartData(regionData, chartRef = null) {
            this.destroyChart(chartRef);
            if (!regionData || !regionData.payment_breakdown) {
                // Return empty dataset if no data is available
                return {
                    labels: [
                        'Customer MC', 'Customer VI', 'Debit', 'Cash', 
                        'Cheque', 'E-transfer', 'Past Credit', 
                        'Gift Card', 'Amex', 'Other'
                    ],
                    datasets: [{
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        backgroundColor: getComputedStyle(document.body).getPropertyValue('--p-cyan-500'),
                        hoverBackgroundColor: getComputedStyle(document.body).getPropertyValue('--p-cyan-500')
                    }]
                };
            }
            const documentStyle = getComputedStyle(document.body);
            
            
            return {
                labels: [
                    'Customer MC', 'Customer VI', 'Debit', 'Cash', 
                    'Cheque', 'E-transfer', 'Past Credit', 
                    'Gift Card', 'Amex', 'Other'
                ],
                datasets: [{
                    data: [
                        regionData.payment_breakdown.Customer_MC,
                        regionData.payment_breakdown.Customer_VI,
                        regionData.payment_breakdown.debit,
                        regionData.payment_breakdown.cash,
                        regionData.payment_breakdown.cheque_draft,
                        regionData.payment_breakdown.etransfer,
                        regionData.payment_breakdown.past_credit,
                        regionData.payment_breakdown.gift_card,
                        regionData.payment_breakdown.amex_payment,
                        regionData.payment_breakdown.other_payment
                    ],
                    backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                    hoverBackgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                }]
            };
        },
        setPieChartOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--text-color');

            return {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            color: textColor,
                            padding: 20,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            };
        },
        async getAgents() {
            try {
                this.loading = true;
                const response = await axios.get('/api/agents');
                this.agents = response.data.agents.map(agent => ({
                        value: agent.id,
                        label:  agent.first_name +' '+ agent.last_name,
                    }));
                this.currentUserId = response.data.current_user_id;
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
        },
        handleAgentChange() {
            if (!this.overallDateRange || this.overallDateRange.length !== 2) return;
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const dateFrom = formatDate(this.overallDateRange[0]);
            const dateTo = formatDate(this.overallDateRange[1]);
            
            this.getAgentSalesCount(dateFrom, dateTo);
        },
        async getAgentSalesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/agent-sales-counts/'+this.selectedAgent+'/'+dateFrom+'/'+dateTo);
                this.agentChartData = this.setAgentChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        setAgentChartData(ChartData, chartRef = null) {
            this.destroyChart(chartRef);
            const documentStyle = getComputedStyle(document.documentElement);

            return {
                labels: ChartData.agent_names,
                datasets: [
                    {
                        label: 'Agents data',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: ChartData.total_sums
                    }
                ]
            };
        },
        setAgentChartOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--p-text-color');
            const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
            const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

            return {
                maintainAspectRatio: false,
                aspectRatio: 0.8,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColorSecondary,
                            font: {
                                weight: 500
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder,
                            drawBorder: false
                        }
                    }
                }
            };
        },
        async getSuppliers() {
            try {
                this.loading = true;
                const response = await axios.get('/api/suppliers');
                this.suppliers = response.data.suppliers.map(supplier => ({
                        value: supplier.id,
                        label:  supplier.first_name +' '+ supplier.last_name,
                    }));
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
        },
        handleSupplierChange() {
            if (!this.overallDateRange || this.overallDateRange.length !== 2) return;
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const dateFrom = formatDate(this.overallDateRange[0]);
            const dateTo = formatDate(this.overallDateRange[1]);
            
            this.getSupplierSalesCount(dateFrom, dateTo);
        },
        async getSupplierSalesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/supplier-sales-counts/'+this.selectedSupplier+'/'+dateFrom+'/'+dateTo);
                this.supplierChartData = this.setSupplierChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        setSupplierChartData(ChartData, chartRef = null) {
            this.destroyChart(chartRef);
            const documentStyle = getComputedStyle(document.documentElement);

            return {
                labels: ChartData.supplier_names,
                datasets: [
                    {
                        label: 'Suppliers data',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: ChartData.total_sums
                    }
                ]
            };
        },
        setSupplierChartOptions() {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--p-text-color');
            const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
            const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

            return {
                maintainAspectRatio: false,
                aspectRatio: 0.8,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColorSecondary,
                            font: {
                                weight: 500
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder,
                            drawBorder: false
                        }
                    }
                }
            };
        },
        getDataStats() {
            if (!this.overallDateRange || this.overallDateRange.length !== 2) return;
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            const dateFrom = formatDate(this.overallDateRange[0]);
            const dateTo = formatDate(this.overallDateRange[1]);
            this.getQueryTypeCount(dateFrom, dateTo);
            this.getLineChartCount(dateFrom, dateTo);
            this.getSupplierSalesCount(dateFrom, dateTo);
            this.getAgentSalesCount(dateFrom, dateTo);
            this.getPaymentBreakdownData(dateFrom, dateTo);
            this.getRegionPaymentBreakdownData(dateFrom, dateTo);
            this.getTableTransactions(dateFrom, dateTo);
            this.getSalesStats(dateFrom, dateTo);
        }
    },
    mounted() {
        this.pieChartOptions = this.setPieChartOptions()
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    beforeUnmount() {
        // Clean up charts when component is destroyed
        Object.values(this.$refs).forEach(chartRef => {
        this.destroyChart(chartRef);
        });
    },
    created() {
        // this.getTableTransactions();
        // this.getQueryTypeCount();
        // this.getLineChartCount();
        // this.getSupplierSalesCount();
        // this.getAgentSalesCount();
        // this.getPaymentBreakdownData();
        // this.getRegionPaymentBreakdownData();
        this.getSuppliers();
        this.getAgents();
        this.getDataStats();

    }
};
</script>
