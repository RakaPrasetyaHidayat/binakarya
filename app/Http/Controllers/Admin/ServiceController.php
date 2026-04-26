<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Service::class);
        $services = Service::orderBy('order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $this->authorize('create', Service::class);
        return view('admin.services.form');
    }

    public function store(ServiceRequest $request)
    {
        $this->authorize('create', Service::class);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $data = $request->validated();
            $data['is_active'] = filter_var($request->boolean('is_active'), FILTER_VALIDATE_BOOLEAN);
            
            // Sanitize HTML content to prevent XSS
            if (isset($data['body'])) {
                $data['body'] = clean($data['body']);
            }
            if (isset($data['excerpt'])) {
                $data['excerpt'] = clean($data['excerpt']);
            }
            
            $service = Service::create($data);
            
            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating service: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->validated(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan layanan. Silakan coba lagi.');
        }
    }

    public function edit(Service $service)
    {
        $this->authorize('update', $service);
        return view('admin.services.form', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $data = $request->validated();
            $data['is_active'] = filter_var($request->boolean('is_active'), FILTER_VALIDATE_BOOLEAN);
            
            // Sanitize HTML content to prevent XSS
            if (isset($data['body'])) {
                $data['body'] = clean($data['body']);
            }
            if (isset($data['excerpt'])) {
                $data['excerpt'] = clean($data['excerpt']);
            }
            
            $service->update($data);
            
            return redirect()
                ->route('admin.services.index')
                ->with('success', 'Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating service: ' . $e->getMessage(), [
                'service_id' => $service->id,
                'user_id' => auth()->id(),
                'request_data' => $request->validated(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui layanan. Silakan coba lagi.');
        }
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $serviceTitle = $service->title;
            $service->delete();
            
            return back()->with('success', "Layanan '{$serviceTitle}' berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Error deleting service: ' . $e->getMessage(), [
                'service_id' => $service->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Gagal menghapus layanan. Silakan coba lagi.');
        }
    }
}
