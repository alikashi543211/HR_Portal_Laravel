 <table class="table table-responsive-md">
     <thead>
         <tr>
             <th class="width80"><strong>Sr.No</strong></th>
             <th><strong>Name</strong></th>
             <th><strong>Code</strong></th>
             <th><strong>Satus</strong></th>
             <th><strong>Request</strong></th>
             <th><strong>Action</strong></th>
         </tr>
     </thead>
     <tbody>
         @foreach ($inventories as $key => $inventory)
             <tr>
                 <td> {{ $inventories->firstItem() + $key }} </td>
                 <td> {{ $inventory->name ?? 'N/A' }} </td>
                 <td> {{ $inventory->code }} </td>
                 <td> {{ $inventory->status == INVENTORY_OFFICE ? 'Office' : 'Home' }} </td>
                 <td>
                     @if ($inventory->request == INVT_REQ_HOME_APPROVED)
                         <div class="badge approved">
                             Approved
                         </div>
                     @elseif($inventory->request == INVT_REQ_HOME_REJECTED)
                         <div class="badge rejected">{{ INVT_REQ_HOME_REJECTED }}</div>
                     @elseif($inventory->request == INVT_REQ_HOME_PENDING)
                         <div class="badge pending">{{ INVT_REQ_HOME_PENDING }}</div>
                     @elseif($inventory->request == INVT_REQ_OFFICE_APPROVED)
                         <div class="badge approved"> Approved </div>
                     @elseif($inventory->request == INVT_REQ_OFFICE_REJECTED)
                         <div class="badge rejected">{{ INVT_REQ_OFFICE_REJECTED }}</div>
                     @elseif($inventory->request == INVT_REQ_OFFICE_PENDING)
                         <div class="badge pending">{{ INVT_REQ_OFFICE_PENDING }}</div>
                     @elseif($inventory->request == INVT_REQ_DEFAULT)
                         {{ INVT_REQ_DEFAULT }}
                     @endif
                 </td>

                 <td class="font-awosome">
                     {{-- @if ($inventory->status == INVENTORY_OFFICE && ($inventory->request == INVT_REQ_HOME_PENDING || $inventory->request == INVT_REQ_HOME_REJECTED || $inventory->request == INVT_REQ_HOME_APPROVED)) --}}
                     {{-- <form action="{{ route('employee.inventories.request') }}" method="POST">
                         @csrf
                             <input type="text" name="id" value="{{ $inventory->id }}" hidden>
                             <input type="text" name="request" value="{{ INVT_REQ_HOME_PENDING }}" hidden>
                             <button class="btn btn-sm  btn-rounded" style="@if ($inventory->request == INVT_REQ_HOME_PENDING) background-color:rgb(139, 139, 139);
                                 @elseif($inventory->request == INVT_REQ_HOME_APPROVED || $inventory->request == INVT_REQ_OFFICE_APPROVED)  background-color: rgb(25, 25, 25);
                                 @elseif ($inventory->request == INVT_REQ_DEFAULT) background-color: rgb(25, 25, 25); 
                                 @elseif ($inventory->request == INVT_REQ_HOME_REJECTED) background-color: rgb(25, 25, 25); @endif  color:#fff" {{ $inventory->request == INVT_REQ_HOME_PENDING ? 'disabled' : '' }} type="submit">
                                 Check-out</button>
                                </form>
                        @elseif($inventory->status == INVENTORY_HOME && ($inventory->request == INVT_REQ_OFFICE_PENDING || $inventory->request == INVT_REQ_OFFICE_REJECTED || $inventory->request == INVT_REQ_HOME_APPROVED))
                                <form action="{{ route('employee.inventories.request') }}" method="POST">
                                    @csrf
                                    <input type="text" name="request" value="{{ INVT_REQ_OFFICE_PENDING }}" hidden>
                                    <input type="text" name="id" value="{{ $inventory->id }}" hidden>
                                    <button class="btn btn-sm btn-rounded" style="@if ($inventory->request == INVT_REQ_HOME_APPROVED) background-color: rgb(255, 119, 52); 
                                        @elseif ($inventory->request == INVT_REQ_DEFAULT) background-color: rgb(255, 119, 52); 
                                        @elseif ($inventory->request == INVT_REQ_OFFICE_REJECTED && $inventory->status == INVENTORY_HOME) background-color: rgb(255, 119, 52); 
                                        @elseif($inventory->request == INVT_REQ_OFFICE_PENDING)  background-color:rgb(139, 139, 139);  
                                        @elseif ($inventory->request == INVT_REQ_OFFICE_REJECTED) background-color: rgb(25, 25, 25); @endif  color:#fff" {{ $inventory->request == INVT_REQ_OFFICE_PENDING ? 'disabled' : '' }} type="submit">Check-in</button>
                                    </form>
                                    @endif --}}

                     <form action="{{ route('employee.inventories.request') }}" method="POST">
                         @csrf
                         <input type="text" name="id" value="{{ $inventory->id }}" hidden>
                         @if ($inventory->status == INVENTORY_OFFICE && ($inventory->request == INVT_REQ_PENDING || $inventory->request == INVT_REQ_REJECTED || $inventory->request == INVT_REQ_APPROVED))
                             <input type="text" name="request" value="{{ INVT_REQ_HOME_PENDING }}" hidden>
                             <button class="btn btn-sm  btn-rounded" style="@if ($inventory->request == INVT_REQ_APPROVED || $inventory->request == INVT_REQ_REJECTED || $inventory->request == INVT_REQ_PENDING || $inventory->request == INVT_REQ_DEFAULT) background-color: #191919; color:white; @endif" type="submit" {{ $inventory->request == INVT_REQ_PENDING ? 'disabled' : '' }}>
                                 Check-out</button>
                         @elseif ($inventory->status == INVENTORY_HOME && ($inventory->request == INVT_REQ_PENDING || $inventory->request == INVT_REQ_REJECTED || $inventory->request == INVT_REQ_APPROVED))
                             <input type="text" name="request" value="{{ INVT_REQ_OFFICE_PENDING }}" hidden>
                             <button class="btn btn-sm  btn-rounded" style="@if ($inventory->request == INVT_REQ_APPROVED || $inventory->request == INVT_REQ_REJECTED || $inventory->request == INVT_REQ_PENDING || $inventory->request == INVT_REQ_DEFAULT) background-color: rgb(255, 119, 52); color:white; @endif" type="submit" {{ $inventory->request == INVT_REQ_PENDING ? 'disabled' : '' }}>
                                 Check-in</button>
                         @endif
                     </form>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
 <div class="custom-pagination">
     {!! $inventories->appends($_GET)->links() !!}
 </div>
