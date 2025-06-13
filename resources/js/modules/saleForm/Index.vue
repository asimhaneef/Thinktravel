<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Sale Form</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create Sale Form
                        </button>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <DataTable  v-model:filters="filters" :value="saleForms"  filterDisplay="menu" :rows="rows" scrollable scrollHeight="400px" 
    sortMode="multiple" tableStyle="min-width: 50rem" :loading="loading"
        :filters="filters"  :rowsPerPageOptions="[10, 20, 50]">
        <!-- Columns for Cruises -->
        <Column field="entry_date" header="Entry Date" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Entry Date" class="form-control" />
            </template>
        </Column>
        <Column field="pnr" header="PNR" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by PNR" class="form-control" />
            </template>
        </Column>
        <Column field="inquiry_no" header="Inquiry No" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Inquiry No" class="form-control" />
            </template>
        </Column>
        <Column field="departure_date" header="Departure Date" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Departure Date" class="form-control" />
            </template>
        </Column>
        <Column field="invoice_date" header="Transaction Date" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Transaction Date" class="form-control" />
            </template>
        </Column>
        <Column field="agent" header="Agent Name" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Agent" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.agent.username }}
            </template>
        </Column>
        <Column field="supplier" header="Supplier" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Supplier" class="form-control" />
            </template>
            <template #body="slotProps">
                {{ slotProps.data.supplier?.username }}
            </template>
        </Column>
        <Column field="last_name" header="Last Name" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Last Name" class="form-control" />
            </template>
        </Column>
        <Column field="total_sale" header="Total Sale" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Total Sale" class="form-control" />
            </template>
        </Column>
        <Column field="margin" header="Margin" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Margin" class="form-control" />
            </template>
        </Column>
        <Column field="cash" header="Cash" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Cash" class="form-control" />
            </template>
        </Column>
        <Column field="balance" header="Balance" sortable :showFilterMatchModes="false">
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text"
                    placeholder="Search by Balance" class="form-control" />
            </template>
        </Column>
        
        <!-- Action Column -->
        <Column field="id" header="Action" style="min-width: 200px">
            <template #body="slotProps">
                <div class="d-flex justify-content-start">
                    <a href="javascript:void(0)" class="m-2 icon-text"
                        @click="printData(slotProps.data)" title="Print">
                        <i class="pi pi-print"></i>
                    </a>
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
    <Sidebar v-model:visible="visibleRight" :header="form.id ? 'Update Sale Form' : 'Add Sale Form'" position="right"
        style="width: 85%;" :dismissable="false">
        <hr />
        <div v-if="isSubmitting" class="loader">Submitting...</div>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : saveSaleForm()"
            @keydown="form.onKeydown($event)">
            <div class="row">
                <div class="form-group col-md-4">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inquiry_code" class="form-label-outside">Entry Date</label>
                            <input id="entry_date" type="date" v-model="form.entry_date" class="form-control entry_date"   />                            
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pnr" class="form-label-outside"> PNR</label>
                            <input id="pnr" type="text" v-model="form.pnr" class="form-control" placeholder="PNR"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="air_domestic" name="sale_type" value="air_usa" /><label for="air_domestic">Air Domestic</label>
                                </div>
                            </div>                   
                        </div>
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="air_intl" name="sale_type" value="air_other" /><label for="air_intl">Air Intl.</label>
                                </div>
                            </div>                   
                        </div>
                    </div>
                    <div class="row bg-column1">
                        <div class="form-group col-md-12">
                            <h2 class="m-0 column-title">Customer Details and Price</h2>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name" class="form-label-outside"> Last Name</label>
                            <input id="last_name" type="text" v-model="form.last_name" class="form-control" placeholder="Last Name"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="first_name" class="form-label-outside"> First Name</label>
                            <input id="first_name" type="text" v-model="form.first_name" class="form-control" placeholder="First Name"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="no_of_pax" class="form-label-outside"> No. of Pax</label>
                            <input id="no_of_pax" type="number" v-model="form.no_of_pax" class="form-control" placeholder="No. of Pax"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ticket_no" class="form-label-outside"> Ticket No.</label>
                            <input id="ticket_no" type="text" v-model="form.ticket_no" class="form-control" placeholder="Ticket No."  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gross_supplier" class="form-label-outside"> Gross Supplier</label>
                            <input id="gross_supplier" type="number" v-model="form.gross_supplier" class="form-control" placeholder="Gross Supplier"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="commission" class="form-label-outside"> Commission</label>
                            <input id="commission" type="number" v-model="form.commission" class="form-control" placeholder="Commission"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="net_cost" class="form-label-outside"> Net Cost</label>
                            <input id="net_cost" type="number" v-model="form.net_cost" class="form-control" placeholder="Net Cost" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="markup" class="form-label-outside"> Markup</label>
                            <input id="markup" type="number" v-model="form.markup" class="form-control" placeholder="Markup"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_sale" class="form-label-outside"> Total Sale</label>
                            <input id="total_sale" type="number" v-model="form.total_sale" class="form-control" placeholder="Total Sale" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_margin" class="form-label-outside"> Total Margin</label>
                            <input id="total_margin" type="number" v-model="form.total_margin" class="form-control" placeholder="Total Margin" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gst" class="form-label-outside"> GST</label>
                            <input id="gst" type="number" v-model="form.gst" class="form-control" placeholder="GST"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="margin_ofgst" class="form-label-outside"> Margin Net of GST</label>
                            <input id="margin_ofgst" type="number" v-model="form.margin_ofgst" class="form-control" placeholder="Margin Net of GST" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="remarks_booking" class="form-label-outside"> Remarks of booking</label>
                            <textarea id="remarks_booking" v-model="form.remarks_booking" class="form-control" placeholder="Remarks Here."></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-6">
                            <label for="trvl_contact_email" class="form-label-outside">Email Address</label>
                            <input id="trvl_contact_email" type="email" v-model="form.trvl_contact_email" class="form-control" placeholder="Email"   />                            
                        </div>
                        <div class="form-group col-md-6">
                            <label for="trvl_contact_no" class="form-label-outside">Contact No.</label>
                            <input id="trvl_contact_no" type="tel" v-model="form.trvl_contact_no" class="form-control" placeholder="Contact No"  />
                        </div>
                    </div>
                </div>
                <!-- Invoice Details Section-->
                <div class="form-group col-md-4">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inquiry_no" class="form-label-outside">Enquiry No</label>
                            <input id="inquiry_no" type="text" v-model="form.inquiry_no" class="form-control inquiry_no" placeholder="Enquiry No"   />                            
                        </div>
                        <div class="form-group col-md-6">
                            <label for="invoice_date" class="form-label-outside"> Transaction Date</label>
                            <input id="invoice_date" type="date" v-model="form.invoice_date" class="form-control" placeholder="Invoice Date"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="vacation" name="sale_type" value="vacation" /><label for="vacation">Vacation</label>
                                </div>
                            </div>                   
                        </div>
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="insurance" name="sale_type" value="insurance" /><label for="insurance">Insurance</label>
                                </div>
                            </div>                   
                        </div>
                    </div>
                    
                    <div class="row bg-column2">
                        <div class="form-group col-md-12">
                            <h2 class="m-0 column-title">Payment to Think Travel</h2>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_recieved" class="form-label-outside"> Total Received</label>
                            <input id="total_recieved" type="number" v-model="form.total_recieved" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="balance_due_payment" class="form-label-outside"> Balance Due</label>
                            <input id="balance_due_payment" type="number" v-model="form.balance_due_payment" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="customer_mc" class="form-label-outside"> Customer MC</label>
                            <input id="customer_mc" type="number" v-model="form.customer_mc" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="customer_vi" class="form-label-outside"> Customer VI</label>
                            <input id="customer_vi" type="number" v-model="form.customer_vi" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="debit" class="form-label-outside"> Debit</label>
                            <input id="debit" type="number" v-model="form.debit" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cash" class="form-label-outside"> Cash</label>
                            <input id="cash" type="number" v-model="form.cash" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cheque_draft" class="form-label-outside"> Cheque Draft</label>
                            <input id="cheque_draft" type="number" v-model="form.cheque_draft" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="etransfer" class="form-label-outside"> ETransfer</label>
                            <input id="etransfer" type="number" v-model="form.etransfer" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="past_credit" class="form-label-outside"> Past Credit</label>
                            <input id="past_credit" type="number" v-model="form.past_credit" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gift_card" class="form-label-outside"> Gift Card</label>
                            <input id="gift_card" type="number" v-model="form.gift_card" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="amex_payment" class="form-label-outside"> Customer Amex</label>
                            <input id="amex_payment" type="number" v-model="form.amex_payment" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="other_payment" class="form-label-outside"> Other Payment</label>
                            <input id="other_payment" type="number" v-model="form.other_payment" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="remarks_payment" class="form-label-outside"> Remarks Payment</label>
                            <textarea id="remarks_payment" v-model="form.remarks_payment" class="form-control" placeholder="Remarks Here."></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-6">
                            <label for="departure_date" class="form-label-outside">Departure Date</label>
                            <input id="departure_date" type="date" v-model="form.departure_date" class="form-control" />                            
                        </div>
                        <div class="form-group col-md-6">
                            <label for="return_date" class="form-label-outside">Return Date</label>
                            <input id="return_date" type="date" v-model="form.return_date" class="form-control"  />                            
                        </div>
                    </div>
                </div>
                <!-- Supplier Details Section-->

                <div class="form-group col-md-4">
                    <div class="row">
                        
                        <div class="form-group col-md-6">
                            <label for="agent" class="form-label-outside">Agent Name </label>                                
                            <Dropdown
                                v-model="form.agent"
                                :options="agents"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Agent"
                                filter
                                showClear
                                class="w-100"                                    
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="supplier" class="form-label-outside">Supplier </label>                                
                            <Dropdown
                                v-model="form.supplier"
                                :options="suppliers"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Select Supplier"
                                filter
                                showClear
                                class="w-100"                                    
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="fee_only" name="sale_type" value="fee_only" /><label for="fee_only">Fee only</label>
                                </div>
                            </div>                   
                        </div>
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <RadioButton v-model="form.sale_type" inputId="balance_due" name="sale_type" value="balance_due" /><label for="balance_due">Balance Due</label>
                                </div>
                            </div>                   
                        </div>
                    </div>
                    
                    <div class="row bg-column3 mb-2">
                        <div class="form-group col-md-12">
                            <h2 class="m-0 column-title">Payment to Supplier</h2>
                        </div>
                        <div class="form-group col-md-6">  
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <Checkbox v-model="form.web_phone" inputId="web_phone" :binary="form.web_phone"  value="Yes"/> 
                                    <label for="web_phone" class="form-label-outside">&nbsp;
                                        Web / Phone &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>                                                  
                        </div>
                        <div class="form-group col-md-6">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <Checkbox v-model="form.gds" inputId="gds" :binary="form.gds"  value="Yes"/> 
                                    <label for="gds" class="form-label-outside">&nbsp;
                                        GDS &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_due" class="form-label-outside">Total Due</label>
                            <input id="total_due" type="number" v-model="form.total_due" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="customer_card" class="form-label-outside">Customer Card</label>
                            <input id="customer_card" type="number" v-model="form.customer_card" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_emax" class="form-label-outside">Company Amex</label>
                            <input id="company_emax" type="number" v-model="form.company_emax" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_visa" class="form-label-outside">Company Visa</label>
                            <input id="company_visa" type="number" v-model="form.company_visa" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_master" class="form-label-outside"> Company Master Card</label>
                            <input id="company_master" type="number" v-model="form.company_master" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="other_supplier" class="form-label-outside">Other</label>
                            <input id="other_supplier" type="number" v-model="form.other_supplier" class="form-control" placeholder="0.00"  />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cheque" class="form-label-outside">Cheque</label>
                            <input id="cheque" type="number" v-model="form.cheque" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="comm_due" class="form-label-outside">Commission Due</label>
                            <input id="comm_due" type="number" v-model="form.comm_due" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-12 mb-5">
                            <label for="cross_check" class="form-label-outside">Cross Check</label>
                            <input id="cross_check" type="number" v-model="form.cross_check" class="form-control" placeholder="0.00" readonly />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="remarks_supplier" class="form-label-outside"> Remarks Supplier</label>
                            <textarea id="remarks_supplier" v-model="form.remarks_supplier" class="form-control" placeholder="Remarks Here."></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-6">
                            <label for="secondary_agent" class="form-label-outside">Secondary Agent</label>        
                            <Dropdown
                            v-model="form.secondary_agent"
                            :options="agents"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Agent" 
                            class="w-100" 
                            id="secondary_agent"                                                               
                        />                    
                        </div>
                        <div class="form-group col-md-6">
                            <label for="secondary_agent_share" class="form-label-outside"> Agent Share (%)</label>
                            <input id="secondary_agent_share" type="number" v-model="form.secondary_agent_share" class="form-control" placeholder="Agent Share (%)" />
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <label for="other_remarks" class="form-label-outside"> Remarks </label>
                    <textarea id="other_remarks" v-model="form.other_remarks" class="form-control" placeholder="Remarks Here."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ editMode ?
                "Update Sale Form" :
                "Create Sale Form"
                }}</button>
        </form>
    </Sidebar>
</template>
<script>
import axios from 'axios';
import { APP_URL } from '../../constants';
import { ref } from 'vue';
import Form from 'vform';

export default {
    components: {
    },
    data() {
        return { 
            APP_URL: APP_URL,
            editMode: false,
            saleForms: [], 
            agents: [],
            suppliers: [],
            currentUserId: null,
            form: new Form({
                id: null,                
                entry_date: '',
                pnr: '',
                sale_type: '',
                location: 'MARLBOROUGH',
                agent: '',
                supplier: '',
                last_name: '',
                first_name: '',
                no_of_pax: '',
                ticket_no: '',
                gross_supplier: '',
                commission: '',
                net_cost: '',
                markup: '',
                total_sale: '',
                total_margin: '',
                gst: '',
                margin_ofgst: '',
                remarks_booking: '',
                return_date: '',
                departure_date: '',
                remarks_payment: '',
                other_payment: '',
                amex_payment: '',
                gift_card: '',
                past_credit: '',
                etransfer: '',
                cheque_draft: '',
                cash: '',
                debit: '',
                customer_vi: '',
                customer_mc: '',
                balance_due_payment: '',
                total_recieved: '',
                invoice_date: '',
                inquiry_no: '',
                other_remarks: '',

                secondary_agent: '',
                secondary_agent_share: '',
                remarks_supplier: '',

                cross_check: '',
                comm_due: '',
                cheque: '',
                other_supplier: '',
                company_master: '',
                company_visa: '',
                company_emax: '',
                customer_card: '',
                total_due: '',
                gds: '',
                web_phone: '',
                trvl_contact_email: '',
                trvl_contact_no: '',
            }),
            loading: false,
            visibleRight: false,
            isSubmitting: false,
            filters: {
                entry_date: { value: null },
                pnr: { value: null },
                inquiry_no: { value: null },
                departure_date: { value: null },
                invoice_date: { value: null },
                agent: { value: null },
                supplier: { value: null },
                last_name: { value: null },
                total_sale: { value: null },
                total_margin: { value: null },
                cash: { value: null },
                balance: { value: null }
            },
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: null,
            page: 1, // Current page number (1-based index)
        };
    },
    watch: {
        'form.sale_type': 'recalculateValues',
        'form.markup': 'recalculateValues',
        'form.commission': 'recalculateValues',
        'form.gross_supplier': 'recalculateValues',
        'form.customer_mc': 'recalculateValues',
        'form.customer_vi': 'recalculateValues',
        'form.debit': 'recalculateValues',
        'form.cash': 'recalculateValues',
        'form.cheque_draft': 'recalculateValues',
        'form.etransfer': 'recalculateValues',
        'form.past_credit': 'recalculateValues',
        'form.gift_card': 'recalculateValues',
        'form.amex_payment': 'recalculateValues',
        'form.other_payment': 'recalculateValues',
        'form.customer_card': 'recalculateValues',
        'form.company_emax': 'recalculateValues',
        'form.company_visa': 'recalculateValues',
        'form.company_master': 'recalculateValues',
        'form.other_supplier': 'recalculateValues',
        'form.cheque': 'recalculateValues',   
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
        
        async getRecords() {
            try {
                this.loading = true;
                const saleFormResponse = await axios.get("/api/sale-form",{
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                // handle success, e.g., show a success message or redirect
                this.saleForms = saleFormResponse.data.data.map(saleForm => {
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
                this.totalRecords = saleFormResponse.data.total;
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting Program:", error);
                this.loading = false;
            } finally {
                this.loading = false; // Set loading to false
            }
        },
        recalculateValues() {
            // Calculate total sale
            const grossSupplier = parseFloat(this.form.gross_supplier) || 0;
            const markup = parseFloat(this.form.markup) || 0;
            this.form.total_sale = (grossSupplier + markup).toFixed(2);
            
            // Calculate total margin (commission + markup)
            const commission = parseFloat(this.form.commission) || 0;
            this.form.total_margin = (commission + markup).toFixed(2);
            
            // Calculate net cost (gross supplier - commission)
            this.form.net_cost = (grossSupplier - commission).toFixed(2);
            
            // Calculate GST if applicable
            const saleType = this.form.sale_type;
            let gst = 0;
            if (saleType === "air_usa" || saleType === "vacation" || saleType === "fee_only") {
                gst = (this.form.total_margin / 1.05) * 0.05;
            }
            this.form.gst = gst.toFixed(2);
            this.form.margin_ofgst = (this.form.total_margin - gst).toFixed(2);
            
            // Calculate total received from customer
            const customerMc = parseFloat(this.form.customer_mc) || 0;
            const customerVi = parseFloat(this.form.customer_vi) || 0;
            const debit = parseFloat(this.form.debit) || 0;
            const cash = parseFloat(this.form.cash) || 0;
            const chequeDraft = parseFloat(this.form.cheque_draft) || 0;
            const etransfer = parseFloat(this.form.etransfer) || 0;
            const pastCredit = parseFloat(this.form.past_credit) || 0;
            const giftCard = parseFloat(this.form.gift_card) || 0;
            const amexPayment = parseFloat(this.form.amex_payment) || 0;
            const otherPayment = parseFloat(this.form.other_payment) || 0;
            
            this.form.total_recieved = (
                customerMc + 
                customerVi + 
                debit + 
                cash + 
                chequeDraft + 
                etransfer + 
                pastCredit + 
                giftCard + 
                amexPayment + 
                otherPayment
            ).toFixed(2);
            
            // Calculate balance due payment
            const customerCard = parseFloat(this.form.customer_card) || 0;
            this.form.balance_due_payment = (
                this.form.total_sale - 
                this.form.total_recieved - 
                customerCard
            ).toFixed(2);
            
            // Calculate supplier payments
            const companyEmax = parseFloat(this.form.company_emax) || 0;
            const companyVisa = parseFloat(this.form.company_visa) || 0;
            const companyMaster = parseFloat(this.form.company_master) || 0;
            const otherSupplier = parseFloat(this.form.other_supplier) || 0;
            
            const chequeCost = this.form.net_cost - (customerCard + companyEmax + companyVisa + companyMaster) + otherSupplier;
            this.form.cheque = (chequeCost > 0 ? chequeCost : 0).toFixed(2);
            this.form.comm_due = (chequeCost > 0 ? 0 : chequeCost * -1).toFixed(2);
            
            // Calculate cross check
            const crossCheckCost = commission - this.form.comm_due;
            this.form.cross_check = (crossCheckCost > 0 ? crossCheckCost : 0).toFixed(2);
            
            // Total due is same as net cost
            this.form.total_due = this.form.net_cost;
        },
        async updateRecord() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/sale-form/${this.form.id}`);
                this.getRecords();
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Record updated successfully', life: 3000 });
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error updating record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
            }
        },
        async saveSaleForm() {
            try {
                this.isSubmitting = true;
                const response = await this.form.post('/api/sale-form');
                this.getRecords();
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Record created successfully', life: 3000 });
                this.form.reset(); // Clear the form data
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
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
        async showSidebar(saleForm) {
            await this.checkAuth();
            this.form.reset();
            if (saleForm) {
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
                    id: saleForm.id,
                    other_remarks: saleForm.other_remarks,
                    entry_date: saleForm.entry_date,
                    pnr: saleForm.pnr,
                    sale_type: saleForm.sale_type,
                    agent: saleForm.agent.id,
                    supplier: saleForm.supplier.id,
                    last_name: saleForm.last_name,
                    first_name: saleForm.first_name,
                    no_of_pax: saleForm.no_of_pax,
                    ticket_no: saleForm.ticket_no,
                    gross_supplier: saleForm.gross_supplier,
                    commission: saleForm.commission,
                    net_cost: saleForm.net_cost,
                    markup: saleForm.markup,
                    gst: saleForm.gst,
                    remarks_booking: saleForm.remarks_booking,
                    return_date: saleForm.return_date,
                    departure_date: saleForm.departure_date,
                    remarks_payment: saleForm.remarks_payment,
                    other_payment: saleForm.other_payment,
                    amex_payment: saleForm.amex_payment,
                    gift_card: saleForm.gift_card,
                    past_credit: saleForm.past_credit,
                    etransfer: saleForm.etransfer,
                    cheque_draft: saleForm.cheque_draft,
                    cash: saleForm.cash,
                    debit: saleForm.debit,
                    customer_vi: saleForm.customer_vi,
                    customer_mc: saleForm.customer_mc,
                    invoice_date: saleForm.invoice_date,
                    inquiry_no: saleForm.inquiry_no,
                    secondary_agent: saleForm.secondary_agent.id,
                    secondary_agent_share: saleForm.secondary_agent_share,
                    remarks_supplier: saleForm.remarks_supplier,
                    other_supplier: saleForm.other_supplier,
                    company_master: saleForm.company_master,
                    company_visa: saleForm.company_visa,
                    company_emax: saleForm.company_emax,
                    customer_card: saleForm.customer_card,
                    gds: saleForm.gds === '1' ? true : false,
                    web_phone: saleForm.web_phone === '1' ? true : false,                    
                    
                    total_sale: saleForm.total_sale,
                    total_margin: saleForm.total_margin,
                    margin_ofgst: saleForm.margin_ofgst,
                    balance_due_payment: saleForm.balance_due_payment,
                    total_recieved: saleForm.total_recieved,
                    cross_check: saleForm.cross_check,

                    comm_due: saleForm.comm_due,
                    cheque: saleForm.cheque,
                    total_due: saleForm.total_due,
                    
                    trvl_contact_email: saleForm.customer_details.member.email,
                    trvl_contact_no: saleForm.customer_details.member.phone_no,
                });
                
                
                this.editMode = true;
            } else {
                this.form.reset();
                this.form.fill({
                    id: null,
                    entry_date: '',
                    pnr: '',
                    sale_type: '',
                    invoice_date: '',
                    inquiry_no: '',
                    location: 'MARLBOROUGH',
                    last_name: '',
                    first_name: '',
                    no_of_pax: '',
                    ticket_no: '',
                    gross_supplier: '',
                    commission: '',
                    net_cost: '',
                    markup: '',
                    remarks_booking: '',
                    customer_mc: '',
                    customer_vi: '',
                    remarks_payment: '',
                    other_payment: '',
                    amex_payment: '',
                    gift_card: '',
                    past_credit: '',
                    etransfer: '',
                    cheque_draft: '',
                    cash: '',
                    debit: '',
                    other_supplier: '',
                    company_master: '',
                    company_visa: '',
                    gst: '',
                    company_emax: '',
                    customer_card: '',
                    gds: '',
                    web_phone: '',
                    remarks_supplier: '',
                    return_date: '',
                    departure_date: '',
                    other_remarks: '',
                    secondary_agent: '',
                    secondary_agent_share: '',
                    agent: '',
                    supplier: '',

                    total_sale: '',
                    total_margin: '',
                    margin_ofgst: '',
                    balance_due_payment: '',
                    total_recieved: '',
                    cross_check: '',
                    comm_due: '',
                    cheque: '',
                    total_due: '',
                    trvl_contact_email: '',
                    trvl_contact_no: '',
                });
                this.editMode = false;
            }
            this.visibleRight = true;
        },
        printData(record) {
            // Open a new window for printing
            const printWindow = window.open('', '_blank');
    
            // Create a print-friendly version of the content
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Sale Form - ${record.id}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2c3e50; }
                        .form-group { margin-bottom: 15px; }
                        .form-label-outside { font-weight: bold; display: block; margin-bottom: 5px; }
                        /* Print-specific layout */
                        @page {
                            size: auto;
                            margin: 10mm;
                        }
                        .print-container {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 15px;
                            width: 100%;
                        }
                        
                        .print-column {
                            flex: 1;
                            min-width: 300px;
                            page-break-inside: avoid;
                        }
                        .bg-column1 { 
                            background-color: #E8E9F9; 
                            padding: 15px;
                            border-radius: 5px;
                            height: 100%;
                        }
                        .bg-column2 { 
                            background-color: #EAF9E8; 
                            padding: 15px;
                            border-radius: 5px;
                            height: 100%;
                        }
                        .bg-column3 { 
                            background-color: #FAFAC3; 
                            padding: 15px;
                            border-radius: 5px;
                            height: 100%;
                        }
                        
                        .column-title { 
                            font-weight: 700; 
                            font-size: 18px; 
                            margin-top: 0;
                            margin-bottom: 15px;
                        }
                        
                        hr { 
                            margin: 20px 0; 
                            border: 0; 
                            border-top: 1px solid #eee; 
                        }
                        
                        @media print {
                            .no-print { 
                                display: none !important; 
                            }
                            body { 
                                padding: 0;
                            }
                            .print-column {
                                page-break-inside: avoid;
                                break-inside: avoid;
                            }
                        }
                    </style>
                </head>
                <body>
                    <h1>Sale Form #${record.id}</h1>
                    <hr>
                    <div class="print-container">
                        <div class="print-column">
                                <div class="form-group">
                                    <label class="form-label-outside">Entry Date</label>
                                    <div>${record.entry_date}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">PNR</label>
                                    <div>${record.pnr}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Sale Type</label>
                                    <div>${record.sale_type}</div>
                                </div>
                        </div>
                        <div class="print-column">
                            <div class="form-group">
                                <label class="form-label-outside">Inquiry No</label>
                                <div>${record.inquiry_no}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-outside">Transaction Date</label>
                                <div>${record.invoice_date}</div>
                            </div>
                        </div>
                        <div class="print-column">
                            <div class="form-group">
                                <label class="form-label-outside">Agent Name</label>
                                <div>${record.agent.username}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-outside">Supplier</label>
                                <div>${record.supplier.username}</div>
                            </div>                    
                        </div>
                    </div>
                        <div class="print-container">
                        <!-- Column 1 -->
                        <div class="print-column">
                            <div class="bg-column1">
                                <h2 class="column-title">Customer Details and Price</h2>
                                <!-- Your column 1 content here -->
                                <div class="form-group">
                                    <label class="form-label-outside">Last Name</label>
                                    <div>${record.last_name}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">First Name</label>
                                    <div>${record.first_name}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">No. of Pax</label>
                                    <div>${record.no_of_pax}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Ticket No.</label>
                                    <div>${record.ticket_no}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Gross Supplier</label>
                                    <div>$${record.gross_supplier}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Commission</label>
                                    <div>$${record.commission}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Net Cost</label>
                                    <div>$${record.net_cost}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Markup</label>
                                    <div>$${record.markup}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Total Sale</label>
                                    <div>$${record.total_sale}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Total Margin</label>
                                    <div>$${record.total_margin}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">GST</label>
                                    <div>$${record.gst}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Margin Net of GST</label>
                                    <div>$${record.margin_ofgst}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Remarks of booking</label>
                                    <div>${record.remarks_booking}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Column 2 -->
                        <div class="print-column">
                            <div class="bg-column2">
                                <h2 class="column-title">Payment to Think Travel</h2>
                                <!-- Your column 2 content here -->
                                <div class="form-group">
                                    <label class="form-label-outside">Total Received</label>
                                    <div>$${record.total_recieved}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Balance Due</label>
                                    <div>$${record.balance_due_payment}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Customer MC</label>
                                    <div>$${record.customer_mc}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Customer VI</label>
                                    <div>$${record.customer_vi}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Debit</label>
                                    <div>$${record.debit}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Cash</label>
                                    <div>$${record.cash}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Cheque Draft</label>
                                    <div>$${record.cheque_draft}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">ETransfer</label>
                                    <div>$${record.etransfer}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Past Credit</label>
                                    <div>$${record.past_credit}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Gift Card</label>
                                    <div>$${record.gift_card}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Customer Amex</label>
                                    <div>$${record.amex_payment}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Other Payment</label>
                                    <div>$${record.other_payment}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Remarks Payment</label>
                                    <div>${record.remarks_payment}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Column 3 -->
                        <div class="print-column">
                            <div class="bg-column3">
                                <h2 class="column-title">Payment to Supplier</h2>
                                <!-- Your column 3 content here -->
                                <div class="form-group">
                                    <label class="form-label-outside">GDS</label>
                                    <div>${record.gds ? 'Yes' : 'No'}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Web/Phone</label>
                                    <div>${record.web_phone ? 'Yes' : 'No'}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Total Due</label>
                                    <div>$${record.total_due}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Customer Card</label>
                                    <div>$${record.customer_card}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Company Amex</label>
                                    <div>$${record.company_emax}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Company Visa</label>
                                    <div>$${record.company_visa}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Company Master Card</label>
                                    <div>$${record.company_master}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Other</label>
                                    <div>$${record.other_supplier}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Cheque</label>
                                    <div>$${record.cheque}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Commission Due</label>
                                    <div>$${record.comm_due}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Cross Check</label>
                                    <div>$${record.cross_check}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label-outside">Remarks Supplier</label>
                                    <div>${record.remarks_supplier}</div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    
                        
                  <div class="print-container" style="margin-top:30px;">
                        <div class="print-column"> 
                            <div class="form-group col-md-6">
                                <label class="form-label-outside">Email Address</label>
                                <div>${record.trvl_contact_email}</div>                           
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label-outside">Contact No.</label>
                                <div>${record.trvl_contact_no}</div>                           
                            </div>
                        </div>
                        <div class="print-column"> 
                            <div class="form-group">
                                <label class="form-label-outside">Departure Date</label>
                                <div>${record.departure_date}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-outside">Return Date</label>
                                <div>${record.return_date}</div>
                            </div>                         
                        </div>
                        <div class="print-column">                          
                            <div class="form-group">
                                <label class="form-label-outside">Secondary Agent</label>
                                <div>${record.secondary_agent ? record.secondary_agent.username : 'N/A'}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-outside">Agent Share (%)</label>
                                <div>${record.secondary_agent_share || '0'}%</div>
                            </div>
                        </div>
                    </div>    
                        
                        
                    
                    <div class="print-container" style="margin-top:30px;">
                        <div class="print-column">
                            <div class="form-group">
                                <label class="form-label">Remarks</label>
                                <div>${record.other_remarks}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <button onclick="window.print()" style="padding: 8px 16px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Print</button>
                        <button onclick="window.close()" style="padding: 8px 16px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Close</button>
                    </div>
                    
                    <script>
                        // Auto-print when the window loads
                        window.addEventListener('load', function() {
                            setTimeout(function() {
                                window.print();
                            }, 200);
                        });
                    <\/script>
                </body></html>`;
            
            // Write the content to the new window
            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();
        },
        formatDate(date) {
            if (!date) return null;
            
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            return `${year}-${month}-${day}`;
        },
        async getRecord(id) {
            try {
                this.loading = true;
                const response = await axios.get(`/api/sale-form/${id}`);
                this.visibleRight = true;
                const today = new Date();
                const formattedDate = today.toLocaleDateString('en-CA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }).replace(/\//g, '-');
                const sortedFlights = [...response.data.booking_flight].sort((a, b) => a.id - b.id);
                this.form.fill({
                    entry_date: formattedDate,
                    inquiryid: response.data.id,
                    inquiry_no: response.data.inquiry_code,
                    invoice_date: formattedDate,
                    agent: response.data.agent.id,
                    last_name: response.data.member.last_name,
                    first_name: response.data.member.first_name,
                    no_of_pax: response.data.children + response.data.adults + response.data.infants,
                    trvl_contact_email: response.data.member.email,
                    trvl_contact_no: response.data.member.phone_no,
                    return_date: sortedFlights.length > 0 
                        ? this.formatDate(sortedFlights[sortedFlights.length - 1].returning_date) 
                        : null,
                    departure_date: sortedFlights.length > 0 
                        ? this.formatDate(sortedFlights[0].departing_date) 
                        : null,
                    pnr: response.data.booking_ref, 
                });
            } catch (error) {
                console.error('Error fetching record:', error);
                this.loading = false;
            }

        },
        onFilter(filters) {
            this.page = 1;
            this.filters = filters;
            this.applyFilter('filter', filters);
        },
        onSort(event) {
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
            this.getRecords();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getRecords();
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
    async created() {
        this.getRecords();
        this.getAgents();
        this.getSuppliers();
        const id = this.$route.params.id;
        if (id) {
            // If there's an ID in the route, we're in edit mode
            await this.getRecord(id);
        }
    },
};
</script>
<style scoped>
.column-title {
    font-weight: 700;
    font-size: 18px;
}
.bg-column1 {
    background-color: #E8E9F9;
    padding: 10px;
    margin-right: 10px;
    border-radius: 5px;
}
.bg-column2 {
    background-color: #EAF9E8;
    padding: 10px;
    border-radius: 5px;
}
.bg-column3 {
    background-color: #FAFAC3;
    padding: 10px;
    margin-left: 10px;
    border-radius: 5px;
}

</style>