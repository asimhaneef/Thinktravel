<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 style="font-size: 32px;font-weight: 700;">Dashboard</h1>
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
                    <div class="small-box bg-info">
                        <div class="inner">
                            <i class="pi pi-comments" style="font-size: 35px;"></i>
                            <h3>{{ open }}</h3>
                            <p>Open</p>
                        </div>
                    
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
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
                    <div class="small-box bg-warning">
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
                    <div class="small-box bg-danger">
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
                            <Chart type="bar" :data="chartData" :options="chartOptions" class="h-[33rem]" />
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
            open: 0,
            cancelled: 0,
            completed: 0,
            inProgress: 0,
            agent: null,
            agents: [],            
            chartData: this.setChartData({
                agents: [],
                open: [],
                cancelled: [],
                completed: [],
                in_progress: []
            }),
            chartOptions: this.setChartOptions(),
            overallDateRange: [firstDay, lastDay],
            APP_URL: APP_URL
        };
    },
    computed: {
    },
    methods: {
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
        async getCalenderInquiriesStats() {
            try {
                const response = await axios.get('/api/calender-inquiry-stats');
                this.calendarOptions.events = response.data;
            } catch (error) {
                console.error('Error fetching posts:', error);
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
            this.getAgentInquiriesCount(dateFrom, dateTo);
            this.getInquiriesCount(dateFrom, dateTo);
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getAgents();
        this.getCalenderInquiriesStats();
        this.getDataStats();
    }
};
</script>
