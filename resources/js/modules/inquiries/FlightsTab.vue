<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquiries</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create Inquiry
                        </button>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <DataTable 
        v-model:filters="filters" 
        :value="flightInquiries" 
        :rows="rows" scrollable  
        dataKey="id"
        filterDisplay="menu" 
        scrollHeight="400px"
        tableStyle="min-width: 50rem" 
        :loading="loading" 
        @update:filters="onFilter" 
        @sort="onSort" 
        @page="onPage"
                            >
        <!-- Columns for Flights -->
        <Column field="inquiry_code" header="Inq. No." sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Inq. No." class="form-control" />
            </template>
        </Column>
        <Column field="created_at" header="Submission Date" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Submission Date" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ formatDate(slotProps.data.created_at) }}
            </template>
        </Column>
        <Column field="booking_ref" header="PNR" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by PNR" class="form-control" />
            </template>
        </Column>
        <Column field="first_name" header="First Name" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by First Name" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.member?.first_name }}
            </template>
        </Column>
        <Column field="last_name" header="Last Name" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Last Name" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.member?.last_name }}
            </template>
        </Column>
        <Column field="phone_no" header="Phone" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Phone" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.member?.phone_no }}
            </template>
        </Column>
        <Column field="email" header="Email" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Email" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.member?.email }}
            </template>
        </Column>
        <Column field="origin" header="Origin" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Origin" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ getOrigin(slotProps.data.booking_flight) }}
            </template>
        </Column>
        <Column field="destination" header="Destination" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Destination" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ getDestination(slotProps.data.booking_flight) }}
            </template>
        </Column>
        <Column field="booking_status" header="Status" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Status" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.booking_status}}
            </template>
        </Column>
        <Column field="agent" header="Agent" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Agent" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.agent?.first_name }} {{ slotProps.data.agent?.last_name }}
            </template>
        </Column>
        <Column field="id" header="Sale Form">
            <template #body="slotProps">
                <router-link :to="`/sale-form-add/${slotProps.data.id}`">Add</router-link>
            </template>
        </Column>
        <!-- Action Column -->
        <Column field="id" header="Action" style="min-width: 200px">
            <template #body="slotProps">
                <div class="d-flex justify-content-start">
                    <a href="javascript:void(0)" class="m-2 icon-text"
                        @click="showSidebar(slotProps.data)" title="Edit">
                        <i class="pi pi-file-edit"></i>
                    </a>
                    <a href="javascript:void(0)"
                        @click="handelDelete($event, slotProps.data.id)" class="m-2 text-danger"
                        title="Delete">
                        <i class="pi pi-trash"></i>
                    </a>
                </div>
            </template>
        </Column>
        <template #empty> No record found. </template>
        <template #loading> Loading records. Please wait. </template>
    </DataTable>
    
    <!-- Paginator Component -->
    <Paginator :first="(page - 1) * rows + 1" :rows="rows" :totalRecords="totalRecords"
        :page="page - 1" :rowsPerPageOptions="[5, 10, 20]" @page="onPageChange" :template="{
            default: 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown JumpToPageInput'
        }">
    </Paginator>
    <div class="d-flex justify-content-between align-items-center mb-3 ml-3 mr-3">
        <div class="listing-count">
            Showing {{ startIndex }} - {{ endIndex }} of {{ totalRecords }} (Page {{ page }})
        </div>
        <div class="rows-per-page">
            <label for="rows">Rows per page:</label>
            <select id="rows" v-model="rows" @change="onPageChange({ page: 0, rows: parseInt($event.target.value) })">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
    
    <Sidebar v-model:visible="visibleRight" :header="form.id ? 'Update Inquiry' : 'Add Inquiry'" position="right"
        style="width: 85%;" :dismissable="false">
        <hr />
        <div v-if="isSubmitting" class="loader">Submitting...</div>
        <form class="mt-3" @submit.prevent="saveEnquiry()"
            @keydown="form.onKeydown($event)">
            <div class="row">
                
                <div class="form-group col-md-12">
                    <label for="flying_type" class="form-label-outside">Type *</label>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <RadioButton v-model="form.flying_type" inputId="return" name="flying_type" value="return" /><label for="return">Return</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <RadioButton v-model="form.flying_type" inputId="one-way" name="flying_type" value="one-way" /><label for="one-way">One Way</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <RadioButton v-model="form.flying_type" inputId="multicity" name="flying_type" value="multicity" /><label for="multicity">Multicity</label>                     
                        </div>
                    </div>                   
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="adults">Adults *</label>
                    <select v-model="form.adults" id="adults" class="form-control" style="background-color:white;" required>
                        <option value="">--Select--</option>
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="children">Children (age 2 - 11) *</label>
                    <select v-model="form.children" id="children" class="form-control" style="background-color:white;" required>
                        <option value="">--Select--</option>
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                <label class="form-label-outside">Infants (age under 2) *</label>
                <select v-model="form.infants" id="infants" class="form-control" style="background-color:white;" required>
                    <option value="">--Select--</option>
                    <option value="0" selected>0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="usa_connection" class="form-label-outside">&nbsp;</label>
                    <br> Avoid USA Connection &nbsp;
                    <Checkbox v-model="form.usa_connection" inputId="usa_connection" :binary="form.usa_connection"  value="Yes"/>                    
                </div>
                <div class="form-group col-md-4" >
                    <label for="customer_identification" class="form-label-outside">Customer Identification</label>
                    <br>
                    <select v-model="form.customer_identification" id="customer_identification" class="form-control input-block-level" style="background-color:white;">
                        <option value="">Select</option>
                        <option value="Web">Web</option>
                        <option value="Walk-in">Walk-in</option>
                        <option value="Phone">Phone</option>
                        <option value="Email">Email</option>
                        <option value="Referral">Referral</option>
                        <option value="Other">Other</option>
                    </select>
                </div>                                      
                    <div class="form-group col-md-2"> 
                        <label for="add_hotel_twoway" class="form-label-outside">&nbsp;</label>
                        <br>Add Hotel &nbsp;&nbsp;
                        
                        <Checkbox v-model="form.add_hotel" :binary="form.add_hotel" inputId="add_hotel_twoway" name="add_hotel_twoway" value="Yes" />
                    </div>
                        
                    <div class="form-group col-md-2" v-if="form.add_hotel">
                        <label class="form-label-outside">Check In Date</label>
                        <input id="checkin_date" type="date" v-model="form.checkin_date" class="form-control date_input" style="background-color:white;" value="" />
                    </div>
                    <div class="form-group col-md-2" v-if="form.add_hotel">
                        <label class="form-label-outside">Check Out Date</label>
                        <input id="checkout_date" type="date" v-model="form.checkout_date" class="form-control date_input" style="background-color:white;" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <br> Travel Document for US / International: <br>
                    </div>
                    <div class="form-group col-md-2">
                        <br>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <RadioButton v-model="form.travel_doc" inputId="passport" name="travel_doc" value="Passport" /><label for="passport">Passport</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <RadioButton v-model="form.travel_doc" inputId="other" name="travel_doc" value="Other" /><label for="other">Other</label>
                        </div>
                    </div>
                         
                    </div>
                    <div class="form-group col-md-4">
                        <div id="div_travel_doc_other" v-if="form.travel_doc === 'Other'"> Please Mention 
                        <input id="travel_doc_other" type="text" v-model="form.travel_doc_other" class="form-control" style="background-color:white;" value="" />
                        </div>
                        <div id="div_travel_doc_passport" v-if="form.travel_doc === 'Passport'"> Nationality &nbsp;&nbsp; 
                            <input id="travel_doc_nationality" type="text" v-model="form.travel_doc_nationality" class="form-control" style="background-color:white;" value="" />
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="mt-3 col-md-12 form-group">
                    <h1 class="py-3 text-xl font-semibold">Flight Details:</h1>
                        <hr />
                </div>
            </div>
                <div v-for="(flightDetail, index) in form.booking_flight" :key="index" class="mt-3 row">
                    <div class="form-group col-md-3">
                        <label :for="'flying_from_' + index" class="form-label-outside">Flying From *</label>                        
                        
                        <Dropdown
                            v-model="flightDetail.flying_from"
                            :options="airports"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Airport"
                            filter
                            showClear
                            class="w-100"
                            required
                        />
                        
                    </div>
                    <div class="form-group col-md-3">
                        <label for="flying_to" class="form-label-outside">Flying To *</label>
                        
                        <Dropdown
                            v-model="flightDetail.flying_to"
                            :options="airports"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Airport"
                            filter
                            showClear
                            class="w-100"
                            required
                        />
                    </div>
                    <div class="form-group col-md-2">
                        <label class="form-label-outside">Departing Date *</label>
                        <input id="departing_date_twoway" type="date" v-model="flightDetail.departing_date" class="form-control date_input" style="background-color:white;" required value="" />
                    </div>
                    <div class="form-group col-md-2" v-if="form.flying_type === 'return'">
                        <label class="form-label-outside">Returning Date *</label>
                        <input id="returning_date_twoway" type="date" v-model="flightDetail.returning_date" class="form-control date_input" onchange="validate_return_date('twoway')" style="background-color:white;" required value="" />
                    </div>
                    <div class="form-group col-md-2">
                        <label class="form-label-outside">Preferred Airline</label>
                        <Dropdown
                            v-model="flightDetail.preferred_airline"
                            :options="airlines"
                            optionLabel="airline_name"
                            optionValue="id"
                            placeholder="Select Airline"
                            filter
                            showClear
                            class="w-100"
                            required
                        />
                    </div>
                    
                    <div class="text-right mt-4 col-md-1" v-if="form.flying_type === 'multicity'">
                        <button type="button" class="btn btn-danger" @click="removeFlightDetail(index)">
                            x
                        </button>
                    </div>
                    <div class="form-group col-md-3" v-if="form.flying_type === 'return'">
                        <label for="registration-last-name" class="form-label-outside">Felxibility</label>
                        <br>
                        <div style="background-color:white;" class="form-control">
                        <input type="radio" v-model="flightDetail.flexibility" id="flexibility" value="1day" /> + / - 1 Day &nbsp;&nbsp;&nbsp; 
                        <input type="radio" v-model="flightDetail.flexibility" id="flexibility" value="3days" /> + / -3 Days
                        </div>
                    </div>
                </div>
                
                <input class="btn btn-primary mb-3" type="button" id="btn_add_flight" @click="addFlightDetail" value="+ Add Another Flight" v-if="form.flying_type === 'multicity'">
                <div class="row">
                <div class="col-md-12">
                    <label for="member_code" class="form-label-outside">Additonal Notes From Customer</label>
                    <textarea id="additional_notes" v-model="form.additional_notes" class="form-control" rows="3" placeholder="Enter any additional notes or requests here..."></textarea>
                    </div>    
                </div> 
                <!--Member info starts-->
                <div class="row">
                    <div class="mt-3 col-md-12 form-group">
                        <h1 class="py-3 text-xl font-semibold">Contact Details:</h1>
                            <hr />
                    </div>
                </div>
                <div id="div_member_info_flights_twoway" class="row">
                    <!--<div class="form-group col-md-4">
                        <label for="member_code" class="form-label-outside">Member Code *</label>
                        <input id="member_code" type="text" v-model="form.member_code" class="form-control" placeholder="Member Code" required />
                    </div>-->
                    <div class="form-group col-md-4">
                        <label for="first_name" class="form-label-outside">First Name *</label>
                        <input id="first_name_twoway" type="text" v-model="form.first_name" class="form-control" placeholder="First Name" required />
                    </div>
                    <div class="form-group col-md-4">
                        <label for="last_name" class="form-label-outside">Last Name *</label>
                        <input id="last_name_twoway" type="text" v-model="form.last_name" class="form-control" placeholder="Last Name" style="text-transform:uppercase;" required />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="contact_no" class="form-label-outside">Contact No *</label>
                        <input @input="formatPhone" id="contact_no_twoway" type="tel" v-model="form.contact_no" class="form-control" placeholder="Contact No" onfocus="this.placeholder ='000-000-0000' " required />
                        <p style="color:red;"></p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="member_email" class="form-label-outside">Email Address *</label>
                        <input id="member_email_flights_twoway" type="email" v-model="form.member_email" class="form-control" placeholder="Email Address" required />
                        <p style="color:red;"></p>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <label for="gender" class="form-label-outside">Gender </label> 
                        <Dropdown
                        v-model="form.gender"
                        :options="genders"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Select Gender"
                        filter
                        showClear
                        class="w-100"
                        required                                    
                    />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="country_id" class="form-label-outside">Country *</label> 
                        <Dropdown
                            v-model="form.country_id"
                            :options="countries"
                            optionLabel="country"
                            optionValue="id"
                            placeholder="Select Country"
                            filter
                            showClear
                            class="w-100"
                            required                                    
                        />           
                    </div>
                    <div class="form-group col-md-4">
                        <label for="payment_mode" class="form-label-outside"> Mode of Payment </label>
                        <br>
                        
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_mode" inputId="cash" name="payment_mode" value="cash" /><label for="cash">Cash</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_mode" inputId="debit_card" name="payment_mode" value="debit_card" /><label for="debit_card">Debit Card</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_mode" inputId="credit_card" name="payment_mode" value="credit_card" /><label for="credit_card">Credit Card</label>                     
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_mode" inputId="other" name="payment_mode" value="other" /><label for="other">Other</label>                     
                            </div>
                        </div>              
                    </div>
                    <div class="form-group col-md-3">
                        <label for="agent_name" class="form-label-outside">Agent Name </label>                                
                        <Dropdown
                            v-model="form.agent_name"
                            :options="agents"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Agent"
                            filter
                            showClear
                            class="w-100"                                    
                        />
                    </div>
                </div>
                <!--Member Info Ends-->
                <div class="row">
                    <div class="mt-3 col-md-12 form-group">
                        <h1 class="py-3 text-xl font-semibold">Admin Options:</h1>
                            <hr />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="customer_contacted" class="form-label-outside">Customer Contacted</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.customer_contacted" inputId="yes" name="customer_contacted" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.customer_contacted" inputId="no" name="customer_contacted" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>

                    <div class="form-group col-md-3">
                        <label for="quote_submitted" class="form-label-outside">Quote Submitted</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.quote_submitted" inputId="yes" name="quote_submitted" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.quote_submitted" inputId="no" name="quote_submitted" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>

                    <div class="form-group col-md-3">
                        <label for="reservation_made" class="form-label-outside">Reservation Made</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.reservation_made" inputId="yes" name="reservation_made" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.reservation_made" inputId="no" name="reservation_made" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>
                    <div class="form-group col-md-3">
                        <label for="payment_received" class="form-label-outside">Payment Received</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_received" inputId="yes" name="payment_received" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.payment_received" inputId="no" name="payment_received" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ticket_issued" class="form-label-outside">* Ticket Issued</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.ticket_issued" inputId="yes" name="ticket_issued" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.ticket_issued" inputId="no" name="ticket_issued" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>
                    <div class="form-group col-md-3">
                        <label for="insurance_purchased" class="form-label-outside">Insurance Purchased</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.insurance_purchased" inputId="yes" name="insurance_purchased" value="1" /><label for="yes">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton v-model="form.insurance_purchased" inputId="no" name="insurance_purchased" value="0" /><label for="no">No</label>
                            </div>
                        </div>                   
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="booking_ref" class="form-label-outside">* PNR Locator</label>
                        <input id="booking_ref" type="text" v-model="form.booking_ref" class="form-control" placeholder="PNR Locator" required />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="booking_status" class="form-label-outside">Booking Status </label>                                
                        <Dropdown
                            v-model="form.booking_status"
                            :options="satatuses"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Status"
                            filter
                            showClear
                            class="w-100"                                    
                        />
                    </div>

                    <div class="form-group col-md-9">
                        <label for="file">Attachments (*)</label>
                        <input type="file" class="form-control" id="file" @change="handleFile">
                        <small class="text-danger" v-if="form.errors.has('file')"
                            v-html="form.errors.get('file')"></small>
                    </div>
                    <div v-if="previous_file" class="form-group col-md-12">
                        <label for="file">Previous File: </label>
                        <a :href="previous_file_path" target="_blank">Download/View</a>
                    </div>
                </div>
                <!--Agent Comments-->
                
                <div class="row">
                    <div class="mt-3 col-md-12 form-group">
                        <h1 class="py-3 text-xl font-semibold">Agent Comments:</h1>
                            <hr />
                    </div>
                </div>
                <div v-for="(detail, index) in form.agent_comments" :key="index" class="mt-3 row">
                    <div class="form-group col-md-2">
                        <label class="form-label-outside" :for="'comment_date_' + index">Comment Date</label>
                        <input :id="'comment_date_' + index" type="date" v-model="detail.created_at" class="form-control date_input" style="background-color:white;" readonly />
                        
                    </div>
                    <div class="form-group col-md-3">
                        <label :for="'agent_name_' + index" class="form-label-outside">Agent Name </label>                                
                        <Dropdown
                            v-model="detail.agent_id"
                            :options="agents"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Agent" 
                            class="w-100" 
                            :id="'agent_name_' + index"
                            disabled                                   
                        />
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label :for="'comment_' + index" class="form-label-outside">Comment</label>
                        <input :id="'comment_' + index" type="text" v-model="detail.comment" class="form-control" placeholder="Add Comments"/>
                    </div>
                    <div class="text-right mt-4 col-md-1" >
                        <button type="button" class="btn btn-danger" @click="removeCommentDetail(index)">
                            x
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="mt-3 col-md-12 form-group">
                        <input class="btn btn-primary mb-3" type="button" id="btn_add_flight" @click="addCommentDetail" value="+ Add Another Comment" >
                    </div>
                </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </Sidebar>
    <Toast ref="toast" />
    <confirmDialog />
</template>

<script>
import axios from 'axios';
import { APP_URL } from '../../constants';
import { ref } from 'vue';
import Form from 'vform';
import { remove } from 'lodash';
export default {
    data() {
        return { 
            APP_URL: APP_URL,
            currentUserId: null,
            form: new Form({
              id: null,
              flying_type: 'return',
              booking_flight: [ // Dynamic section for receipt details
                    {
                        flying_from: '',
                        flying_to: '',
                        departing_date: '',
                        returning_date: '',
                        preferred_airline: '',
                        flexibility: ''
                    }
                ],
                agent_comments:[
                    {
                        comment: '',
                        agent_id: '', // Set agent_id dynamically
                        created_at: new Date().toLocaleDateString('en-CA'),
                    }
                ],
                inquiry_code: "",
                booking_ref: "",
                adults: '',
                children: '',
                infants: '',
                usa_connection: true,
                customer_identification: '',
                add_hotel: false,
                travel_doc: '',
                travel_doc_other: '',
                travel_doc_nationality: '',
                booking_type: 'flights',
                booking_status: 'Open',
                checkin_date: '',
                checkout_date: '',
                member_email: '',
                contact_no: '',
                first_name: '',
                last_name: '',
                gender : '',
                agent_name : '',
                country_id : '',
                additional_notes: '',
                payment_mode: '',
                customer_contacted: '',
                quote_submitted: '',
                reservation_made: '',
                payment_received: '',
                ticket_issued: '',
                insurance_purchased: '',
                file: null,
            }),
            
            previous_file: false,
            previous_file_path: null,
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            initialLoad: true,
            flightInquiries: [],           
            airports: [],
            airlines: [],
            countries: [],
            agents: [],
            genders: [
                { label: 'Male', value: 'Male' },
                { label: 'Female', value: 'Female' },
                { label: 'Other', value: 'Other' },
            ],
            satatuses: [
                { label: 'Open', value: 'Open' }, 
                { label: 'In-Progress', value: 'In-Progress' }, 
                { label: 'Completed', value: 'Completed' }, 
                { label: 'Cancelled', value: 'Cancelled' },
            ],
            filters: {
                inquiry_code: { value: null },
                first_name: { value: null },
                last_name: { value: null },
                email: { value: null },
                phone_no: { value: null },
                booking_ref: { value: null },
                flying_type: { value: null },
                booking_status: { value: null },
                agent: { value: null },
                origin: { value: null },
                destination: { value: null },
            },
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: null,
            page: 1, // Current page number (1-based index)
        }
    },
    mounted() {
        this.getAgents();
    },
    methods: {
        async checkAuth() {
            try {
                // Make a request to a protected endpoint to check authentication
                await axios.get('/api/region-category'); // Replace with your auth check endpoint
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    // Redirect to login page if unauthorized
                    window.location.href = '/login'; // Replace with your login route
                }
            }
        },
      formatPhone(e) {
        // Get only digits
        const input = e.target.value.replace(/\D/g, '').substring(0, 10)
        this.cleanPhone = input
        
        // Format with mask
        if (input.length > 0) {
          let formatted = input.substring(0, 3)
          if (input.length > 3) {
            formatted += '-' + input.substring(3, 6)
          }
          if (input.length > 6) {
            formatted += '-' + input.substring(6, 10)
          }
          this.form.contact_no = formatted
        } else {
          this.form.contact_no = ''
        }
      },
        formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options);
        },
        getOrigin(bookingFlights) {
            if (bookingFlights && bookingFlights.length > 0) {
                const firstFlight = bookingFlights[0];
                return firstFlight.flight_from ? firstFlight.flight_from.airport_name : "N/A";
            }
            return "N/A";
        },
        
        addFlightDetail() {
            this.form.booking_flight.push({
                flying_from: '',
                flying_to: '',
                departing_date: '',
                returning_date: '',
                preferred_airline: '',
                flexibility: '',
            });
        },
        addCommentDetail() {
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true // Use 12-hour format (true) or 24-hour format (false)
            };
            this.form.agent_comments.push({
                comment: '',
                agent_id: this.currentUserId,
                created_at: new Date().toLocaleDateString('en-CA'),
            })
        },
        removeCommentDetail(index) {
            this.form.agent_comments.splice(index, 1);
        },
        removeFlightDetail(index) {
            if (this.form.booking_flight.length > 1) {
                this.form.booking_flight.splice(index, 1);
            } else {
                this.$toast.add({ severity: 'warn', summary: 'Warning', detail: 'At least one Flight detail is required', life: 3000 });
            }
        },
        getDestination(bookingFlights) {
            if (bookingFlights && bookingFlights.length > 0) {
                const lastFlight = bookingFlights[bookingFlights.length - 1];
                return lastFlight.flight_to ? lastFlight.flight_to.airport_name : "N/A";
            }
            return "N/A";
        },
        
        async saveEnquiry() {
            this.isSubmitting = true; // Show loader
            const formData = new FormData();

            // Append form data to FormData object
            for (const key in this.form) {
                if (this.form.hasOwnProperty(key)) {
                    const value = this.form[key];

                    // Handle file uploads
                    if (key === 'file' && value) {
                        formData.append(key, value);
                    }
                    // Handle nested objects/arrays (e.g., booking_flight, agent_comments)
                    else if (typeof value === 'object' && value !== null) {
                        formData.append(key, JSON.stringify(value)); // Serialize objects/arrays
                    }
                    // Handle primitive values (strings, numbers, booleans)
                    else {
                        formData.append(key, value);
                    }
                }
            }

            try {
                // Send the form data to the server
                const response = await axios.post('/api/inquiry', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                // Show success message
                if (this.form.id) {
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record updated successfully', life: 3000 });
                } else {
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record created successfully', life: 3000 });
                }
            } catch (error) {
                // Show error message
                if (this.form.id) {
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error updating record', life: 3000 });
                } else {
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating record', life: 3000 });
                }

                // Set form errors if any
                if (error.response && error.response.data && error.response.data.errors) {
                    this.form.errors.set(error.response.data.errors);
                }

                console.error('Error saving post:', error);
            } finally {
                // Refresh inquiries and hide loader
                this.getInquiries();
                this.isSubmitting = false;
                this.loading = false;
            }
        }, 
        async handelDelete(event, id) {
            this.$confirm.require({
                target: event.currentTarget,
                message: 'Do you want to delete this record?',
                icon: 'pi pi-info-circle',
                rejectProps: {
                    label: 'Cancel',
                    severity: 'secondary',
                    outlined: true
                },
                acceptProps: {
                    label: 'Delete',
                    severity: 'danger'
                },
                accept: () => {
                    console.log(id);
                    this.deleteRecord(id); // Proceed with the deletion

                },
            });
        },
        async deleteRecord(id) {
                try {
                    this.isSubmitting = true;
                    const response = await axios.delete(`/api/inquiry/${id}`);
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record deleted successfully', life: 3000 });
                    
                } catch (error) {
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
                    
                } finally {
                    this.isSubmitting = false; // hide loader
                    this.loading = false;
                    this.getInquiries();
                }
        },       
        handleFile(event) {
            const file = event.target.files[0];
            this.form.file = file;
        },
        async getInquiries() {
            try {
                this.loading = true;
                const flightResponse = await axios.get("/api/flight-inquiry",{
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                // handle success, e.g., show a success message or redirect
                this.flightInquiries = flightResponse.data.data;
                this.totalRecords = flightResponse.data.total;
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting Program:", error);
                this.loading = false;
            } finally {
                this.loading = false; // Set loading to false
            }
        },
      async getAirports() {
          try {
              this.loading = true;
              const response = await axios.get('/api/airport');
              this.airports = response.data.data.map(airport => ({
                        value: airport.id,
                        label:  airport.iata +' '+ airport.airport_name +' '+ airport.city?.city_name +' '+ airport.city?.country?.country,
                    }));
              this.loading = false;
          } catch (error) {
              console.error('Error fetching Cities:', error);
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
      async getCountries() {
            try {
                this.loading = true;
                const response = await axios.get('/api/country');
                this.countries = response.data.data;
                // Find the default country where is_default is 1
                const defaultCountry = this.countries.find(country => country.is_default === 1);

                // Set the default country in the form
                if (defaultCountry) {
                    this.form.country_id = defaultCountry.id;
                }
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
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
        async showSidebar(inquiry) {
            await this.checkAuth();
            if (inquiry) {
                const parseDate = (dateString) => {
                    if (!dateString) return null;

                    // Handle datetime format (e.g., "2025-03-21T23:06:19.000000Z")
                    if (dateString.includes('T')) {
                        const date = new Date(dateString);
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`; // Convert to YYYY-MM-DD
                    }

                    // Handle DD/MM/YYYY format
                    const [day, month, year] = dateString.split('/');
                    return `${year}-${month}-${day}`; // Convert to YYYY-MM-DD
                };

                
                this.form.fill({
                    flying_type: inquiry.flight_type,
                    booking_type: inquiry.booking_type,
                    inquiry_code: inquiry.inquiry_code,
                    booking_ref: inquiry.booking_ref,

                    first_name: inquiry.member.first_name,
                    last_name: inquiry.member.last_name,
                    member_email: inquiry.member.email,
                    contact_no: inquiry.member.phone_no,
                    gender: inquiry.member.gender,
                    agent_name: inquiry.agent_name,
                    country_id: inquiry.member.country_id,
                    payment_mode: inquiry.payment_mode,

                    
                    adults: inquiry.adults,
                    children: inquiry.children,
                    infants: inquiry.infants,
                    usa_connection: inquiry.usa_connection === 1 ? true : false,
                    customer_identification: inquiry.customer_identification,
                    add_hotel: inquiry.add_hotel === 1 ? true : false,
                    travel_doc: inquiry.travel_doc,
                    travel_doc_other: inquiry.travel_doc_other,
                    travel_doc_nationality: inquiry.travel_doc_nationality,
                    checkin_date: inquiry.hotel_checkin_date,
                    checkout_date: inquiry.hotel_checkout_date,
                    customer_contacted: inquiry.customer_contacted === 1 ? '1' : '0',
                    quote_submitted: inquiry.quote_submitted === 1 ? '1' : '0',
                    reservation_made: inquiry.reservation_made === 1 ? '1' : '0',
                    payment_received: inquiry.payment_received === 1 ? '1' : '0',
                    ticket_issued: inquiry.ticket_issued === 1 ? '1' : '0',
                    insurance_purchased: inquiry.insurance_purchased === 1 ? '1' : '0',
                    booking_status: inquiry.booking_status,
                    additional_notes: inquiry.additional_notes || '',
                    






                    booking_flight: inquiry.booking_flight ? inquiry.booking_flight.map(flight => ({
                        flying_from: flight.flying_from,
                        flying_to: flight.flying_to,
                        departing_date: flight.departing_date,
                        returning_date: flight.returning_date,
                        preferred_airline: flight.preferred_airline,
                        flexibility: flight.flexibility
                    })): [{
                            flying_from: '',
                            flying_to: '',
                            departing_date: '',
                            returning_date: '',
                            preferred_airline: '',
                            flexibility: ''
                        }],
                        agent_comments: inquiry.agent_comments ? inquiry.agent_comments.map(comment => ({ 
                            comment: comment.comment, 
                            agent_id: comment.agent_id, 
                            created_at: parseDate(comment.created_at )
                        })) : [{
                                comment: '',
                                agent_id: this.currentUserId, // Set agent_id dynamically
                                created_at: new Date().toLocaleDateString('en-CA'),
                            }],
                    id: inquiry.id, // Fill form with category details

                });
                
                if (inquiry.files ) {
                    this.previous_file = inquiry.files.file_name;
                    this.previous_file_path = APP_URL+inquiry.files.file_real_path;
                }else{
                    this.previous_file = null;
                    this.previous_file_path = null;
                }
                this.editMode = true;
            } else {
                this.form.reset();
                this.form.fill({
                    flying_type: 'return',
                    booking_type: 'flights',
                    booking_flight: [ // Initialize as an array
                        {
                            flying_from: '',
                            flying_to: '',
                            departing_date: '',
                            returning_date: '',
                            preferred_airline: '',
                            flexibility: ''
                        }
                    ],
                    agent_comments: [{
                                comment: '',
                                agent_id: this.currentUserId, // Set agent_id dynamically
                                created_at: new Date().toLocaleDateString('en-CA'),
                            }],
                    inquiry_code: "",
                    booking_ref: "",
                    adults: '',
                    children: '',
                    infants: '',
                    usa_connection: false,
                    customer_identification: '',
                    add_hotel: false,
                    travel_doc: '',
                    travel_doc_other: '',
                    travel_doc_nationality: '',
                    checkin_date: '',
                    checkout_date: '',
                    member_email: '',
                    contact_no: '',
                    first_name: '',
                    last_name: '',
                    gender: '',
                    agent_name: '',
                    country_id: '',
                    payment_mode: '',
                    customer_contacted: '',
                    quote_submitted: '',
                    reservation_made: '',
                    payment_received: '',
                    ticket_issued: '',
                    insurance_purchased: '',
                    booking_status: 'Open',

                    file: null,
                    previous_file: false,
                    previous_file_path: null,
                });
                this.editMode = false;
            }
            this.visibleRight = true;
        },
        confirmDelete(id) {
            this.$confirm.require({
                message: 'Are you sure you want to delete this record?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.deleteRecord(id);
                },
                reject: () => {
                    // Do nothing on reject
                }
            });
        },
        
        onFilter(filters) {
            this.page = 1;
            this.filters = filters;
            this.applyFilter('filter', filters);
        },
        onSort(event) {
            console.log(event);
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.applyFilter('sort', event);
        },
        onPage(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.applyFilter('page', event);
        },
        applyFilter(actionType, event) {
            this.getInquiries();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getInquiries();
        }
    },
    
    computed: {
        startIndex() {
            return (this.page - 1) * this.rows + 1;
        },
        endIndex() {
            const end = this.page * this.rows;
            return end > this.totalRecords ? this.totalRecords : end;
        },
    },
    
    created() {
        this.getInquiries();
        this.getAirports();
        this.getAirlines();
        this.getCountries();
        this.getAgents();
    },
};
</script>