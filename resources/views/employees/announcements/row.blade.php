 <table class="table table-responsive-md">
     <thead>
         <tr>
             <th class="width80"><strong>Sr.No</strong></th>
             <th><strong>Title</strong></th>
             <th><strong>Date</strong></th>
             <th><strong>Actions</strong></th>
         </tr>
     </thead>
     <tbody>
         @foreach ($announcements as $key => $announcement)
             <tr>
                 <td><strong>{{ $key + $announcements->firstItem() }}</strong></td>
                 <td>{{ $announcement->title }}</td>
                 <td>{{ \Carbon\Carbon::parse($announcement->created_at)->format('Y-m-d') }}</td>
                 <td class="font-awosome">
                     <a href="{{ route('employee.announcements.detail', $announcement->id) }}" class="mr-2"><i class="fa-regular fa-eye"></i></a>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
 <div class="custom-pagination">
     {!! $announcements->appends($_GET)->links() !!}
 </div>
