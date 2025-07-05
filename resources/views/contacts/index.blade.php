@extends('layouts.app')

@section('title', 'Contact List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-address-book"></i> Contacts</h1>
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Contact
            </a>
        </div>

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('contacts.index') }}" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search by name, email or phone..."
                                   value="{{ $search ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    @if($search)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-2">Results for:</span>
                                <span class="badge bg-primary me-2">{{ $search }}</span>
                                <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear search
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if($contacts->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created at</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td>
                                            <strong>{{ $contact->name }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $contact->email }}">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $contact->phone }}">
                                                {{ $contact->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $contact->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('contacts.show', $contact->id) }}"
                                                   class="btn btn-outline-primary"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contacts.edit', $contact->id) }}"
                                                   class="btn btn-outline-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('contacts.destroy', $contact->id) }}"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }}
                            of {{ $contacts->total() }} contacts
                            @if($search)
                                <span class="text-muted">(filtered by "{{ $search }}")</span>
                            @endif
                        </div>
                        <div>
                            {{ $contacts->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                    @if($search)
                        <h4>No contacts found</h4>
                        <p class="text-muted">No contacts were found with the term "{{ $search }}".</p>
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-list"></i> View all contacts
                        </a>
                        <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Contact
                        </a>
                    @else
                        <h4>No contacts found</h4>
                        <p class="text-muted">Start by creating your first contact.</p>
                        <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create First Contact
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = document.querySelector('form');
    let searchTimeout;

    // Auto-submit search form after user stops typing (debounced)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2 || this.value.length === 0) {
                searchForm.submit();
            }
        }, 500);
    });

    // Focus on search input when page loads if there's a search term
    if (searchInput.value) {
        searchInput.focus();
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }

    // Handle keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+F or Cmd+F focuses on search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }

        // Escape clears search
        if (e.key === 'Escape' && searchInput.value) {
            searchInput.value = '';
            searchForm.submit();
        }
    });
});
</script>
@endpush
