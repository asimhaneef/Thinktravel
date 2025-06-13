<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 style="font-size: 32px;font-weight: 700;">Inquiries Dashboard</h1>
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
                    <div class="small-box card">
                        <div class="inner">
                            <i class="pi pi-comments" style="font-size: 35px;"></i>
                            <h3>{{ open }}</h3>
                            <p>Open</p>
                        </div>                        
                    </div>
                    <!-- small box -->
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <i class="pi pi-spinner pi-spin" style="font-size: 35px;"></i>
                            <h3>{{ inProgress }}</h3>
                            <p>In Progress</p>
                        </div>                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <i class="pi pi-check-circle" style="font-size: 35px;"></i>
                            <h3>{{ completed }}</h3>
                            <p>Completed</p>
                        </div>                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box card">
                        <div class="inner">
                            <i class="pi pi-times-circle" style="font-size: 35px;"></i>
                            <h3>{{ cancelled }}</h3>

                            <p>Cancelled</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div>
        <div class="container-fluid text-center">
            <div class="row">
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;"><i class="pi pi-chart-bar"></i> Query Count</p>
                            <Calendar 
                                v-model="dateRange" 
                                selectionMode="range" 
                                :manualInput="false" 
                                dateFormat="yy-mm-dd"
                                placeholder="Select a Date Range"
                                showIcon
                                @update:modelValue="getDateRangeInquiriesCount"
                                class="float-end"
                            />
                        </div>
                        <div class="card-body">
                            <Chart type="line" :data="lineChartData" :options="lineChartOptions" class="h-[30rem]" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;"><i class="pi pi-chart-bar"></i> Agents Graph</p>
                            <Dropdown
                                v-model="agent"
                                :options="agents"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Agent"
                                filter
                                showClear
                                @change="handleAgentChange"
                                class="col-4 w-100 float-end"                                    
                            />
                        </div>
                        <div class="card-body">
                            <Chart type="bar" :data="chartData" :options="chartOptions" class="h-[30rem]" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> Calendar
                            </p>
                            <span class="d-flex float-end mt-3"><i class="pi pi-stop float-end cursor-pointer mr-1" style="background-color: lightgreen;margin-top:6px;" /> Open </span>
                            <span class="d-flex float-end mt-3 mr-2"><i class="pi pi-stop float-end cursor-pointer mr-1" style="background-color: lightblue;margin-top:6px;" /> In-Progress </span>
                            <span class="d-flex float-end mt-3 mr-2"><i class="pi pi-stop float-end cursor-pointer mr-1" style="background-color: lightgoldenrodyellow;margin-top:6px;" /> Completed </span>
                            <span class="d-flex float-end mt-3 mr-2"><i class="pi pi-stop float-end cursor-pointer mr-1" style="background-color: lightgrey;margin-top:6px;" /> Cancelled </span>

                        </div>
                        <div class="card-body">
                            <FullCalendar :options="calendarOptions" />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> Queries
                            </p>
                            <i class="pi pi-arrow-right float-end cursor-pointer mt-3" @click="navigateToInquiries"></i>
                        </div>
                        <div class="card-body h-[36rem]">
                            <DataTable :value="Inquiries" tableStyle="min-width: 50rem">
                                <Column field="inquiry_code" header="QueryID"></Column>
                                <Column field="created_at" header="Date">
                                    <template #body="slotProps">
                                        <span>{{ formatDate(slotProps.data.created_at) }}</span>
                                    </template>
                                </Column>
                                <Column field="agent" header="Agent">
                                    <template #body="slotProps">
                                        <span>{{ slotProps.data.agent.first_name }} {{ slotProps.data.agent.last_name }}</span>
                                    </template>
                                </Column>
                                <Column field="booking_status" header="Status"></Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;">
                                <i class="pi pi-chart-bar"></i> Destination Queries
                            </p>
                        </div>
                        <div class="card-body">
                            <Chart 
                                type="bar" 
                                :data="destinationQueriesChartData" 
                                :options="destinationQueriesChartOptions" 
                                class="h-[30rem]" 
                            />
                        </div>
                    </div>
                </div>
                <div class="mb-5 col-6">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title fs-2" style="font-weight: 400;font-size: x-large;"><i class="pi pi-chart-bar"></i> Most Booked Flights</p>
                            <Dropdown
                            v-model="airline"
                            :options="airlines"
                            optionLabel="airline_name"
                            optionValue="id"
                            placeholder="Select Airline"
                            filter
                            showClear
                            class="col-4 w-100 float-end"
                            @change="handleAirlineChange"
                            required
                            />
                        </div>
                        <div class="card-body">
                            <Chart type="bar" :data="barChartData" :options="barChartOptions" class="h-[30rem]"  />
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
import Form from 'vform';
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
                airline: null,
                open: 0,
            cancelled: 0,
            completed: 0,
            inProgress: 0,
            dateRange: [firstDay, lastDay],
            overallDateRange: [firstDay, lastDay],
            lineChartData: this.setLineChartData({
                dates: [],
                counts: {
                    open: [],
                    cancelled: [],
                    completed: [],
                    in_progress: []
                }
            }),
            lineChartOptions: this.setLineChartOptions(),
            categories: [],
            agent: null,
            agents: [],
            airlines: [],
            queryCount: [],
            chartData: this.setChartData({
                agents: [],
                open: [],
                cancelled: [],
                completed: [],
                in_progress: []
            }),
            chartOptions: this.setChartOptions(),
            Inquiries: [],
            barChartData: this.setBarChartData({
                airlines: [],
                count: []
            }),
            barChartOptions: this.setBarChartOptions(),
            destinationQueriesChartData: this.setdestinationQueriesChartData([]),
            destinationQueriesChartOptions: this.setdestinationQueriesChartOptions(),
            APP_URL: APP_URL
        };
    },
    computed: {
        filteredCategories() {
            return this.categories.filter(category => category.posts && category.posts.length > 0);
        }
    },
    methods: {        
        formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options);
        },
        navigateToInquiries() {
            // If you're using Vue Router
            this.$router.push('/inquiries');
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
        async getInquiriesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/inquiry-counts/'+dateFrom+'/'+dateTo);
                this.open = response.data.open;
                this.cancelled = response.data.cancelled;
                this.completed = response.data.completed;
                this.inProgress = response.data.in_progress;
                console.log('Inquiries with count:', response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
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
            this.getAgentInquiriesCount(dateFrom, dateTo);
        },
        async getAgentInquiriesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/agent-inquiry-counts/'+this.agent+'/'+dateFrom+'/'+dateTo);
                    this.chartData = this.setChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async getDateRangeInquiriesCount() {
            if (!this.dateRange || this.dateRange.length !== 2) return;
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const dateFrom = formatDate(this.dateRange[0]);
            const dateTo = formatDate(this.dateRange[1]);
            try {
                const response = await axios.get('/api/daterange-inquiry-counts/'+dateFrom+'/'+dateTo);
                this.lineChartData = this.setLineChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async getTableInquiries(dateFrom, dateTo) {
            try {
                this.loading = true;
                const Response = await axios.get("/api/inquiry",{
                    params: {
                        rows: 8,
                        page: 1,
                        dateFrom: dateFrom,
                        dateTo: dateTo
                    }
                });
                // handle success, e.g., show a success message or redirect
                this.Inquiries = Response.data.data;
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting Program:", error);
                this.loading = false;
            } finally {
                this.loading = false; // Set loading to false
            }
        },
        handleAirlineChange() {
            if (!this.overallDateRange || this.overallDateRange.length !== 2) return;
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const dateFrom = formatDate(this.overallDateRange[0]);
            const dateTo = formatDate(this.overallDateRange[1]);
            this.getAirlineInquiriesCount(dateFrom, dateTo);
        },
        async getAirlineInquiriesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/airline-inquiry-counts/'+this.airline+'/'+dateFrom+'/'+dateTo);
                    this.barChartData = this.setBarChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async getRegionInquiriesCount(dateFrom, dateTo) {
            try {
                const response = await axios.get('/api/region-inquiry-counts/'+dateFrom+'/'+dateTo);
                this.destinationQueriesChartData = this.setdestinationQueriesChartData(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        setLineChartData(chartData) {
            const documentStyle = getComputedStyle(document.documentElement);

            return {
                labels: chartData.dates,
                datasets: 
                [
                    {
                        label: 'Open',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: chartData.counts.open
                    },
                    {
                        label: 'In Progress',
                        backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                        data: chartData.counts.in_progress
                    },
                    {
                        label: 'Completed',
                        backgroundColor: documentStyle.getPropertyValue('--p-orange-500'),
                        data: chartData.counts.completed
                    },
                    {
                        label: 'Cancelled',
                        backgroundColor: documentStyle.getPropertyValue('--p-red-500'),
                        data: chartData.counts.cancelled
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
        setChartData(chartData) {
            const documentStyle = getComputedStyle(document.documentElement);

            return {
                labels: chartData.agents, // This will be the array of agent names
                datasets: [
                    {
                        type: 'bar',
                        label: 'Open',
                        backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                        data: chartData.open || [] // Use the open counts from backend
                    },
                    {
                        type: 'bar',
                        label: 'In Progress',
                        backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                        data: chartData.in_progress || [] // Use the in-progress counts from backend
                    },
                    {
                        type: 'bar',
                        label: 'Completed',
                        backgroundColor: documentStyle.getPropertyValue('--p-orange-500'),
                        data: chartData.completed || [] // Use the completed counts from backend
                    },
                    {
                        type: 'bar',
                        label: 'Cancelled',
                        backgroundColor: documentStyle.getPropertyValue('--p-red-500'),
                        data: chartData.cancelled || [] // Use the cancelled counts from backend
                    }
                ]
            };
        },
        setChartOptions() {
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
        setBarChartData(chartData) {
            const documentStyle = getComputedStyle(document.documentElement);
            return {
                labels: chartData.airlines,
                datasets: [
                    {
                        label: 'Bookings by Airline',
                        backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                        borderColor: documentStyle.getPropertyValue('--p-surface-500'),
                        data: chartData.count
                    }
                ]
            };
        },
        setBarChartOptions() {
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
        setdestinationQueriesChartData(chartData) {
            const documentStyle = getComputedStyle(document.documentElement);
            
            return {
                labels: chartData.regions,
                datasets: [
                    {
                        label: 'Cruise Inquiries by Region',
                        backgroundColor: documentStyle.getPropertyValue('--p-gray-500'),
                        borderColor: documentStyle.getPropertyValue('--p-surface-500'),
                        data: chartData.counts
                    }
                ]
            };
        },
        setdestinationQueriesChartOptions() {
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
        async getAgents() {
            try {
                this.loading = true;
                const response = await axios.get('/api/agents/');
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
        async getAirlines() {
            try {
                this.loading = true;
                const response = await axios.get('/api/airline');
                this.airlines = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        async getCalenderInquiriesStats() {
            try {
                const response = await axios.get('/api/calender-inquiry-stats');
                this.calendarOptions.events = response.data;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
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
            this.getInquiriesCount(dateFrom, dateTo);
            this.getAgentInquiriesCount(dateFrom, dateTo);
            this.getAirlineInquiriesCount(dateFrom, dateTo);
            this.getRegionInquiriesCount(dateFrom, dateTo);
            this.getTableInquiries(dateFrom, dateTo);
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getDataStats();
        this.getDateRangeInquiriesCount();
        // this.getInquiriesCount();
        // this.getAgentInquiriesCount();
        this.getAgents();
        this.getAirlines();
        // this.getTableInquiries();
        // this.getAirlineInquiriesCount();
        // this.getRegionInquiriesCount();
        this.getCalenderInquiriesStats();
    }
};
</script>
