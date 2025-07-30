<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Golongan;
use App\Services\ImageService;
use Exception;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Show profile edit form for dosen
     */
    public function editDosen()
    {
        try {
            $dosen = Auth::guard('dosen')->user();
            
            if (!$dosen) {
                Log::warning('Unauthenticated user trying to access dosen profile edit');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            return view('profile.edit-dosen', compact('dosen'));
        } catch (Exception $e) {
            Log::error('Error accessing dosen profile edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengakses halaman profile.');
        }
    }

    /**
     * Update dosen profile
     */
    public function updateDosen(Request $request)
    {
        try {
            $dosen = Auth::guard('dosen')->user();
            
            if (!$dosen) {
                Log::warning('Unauthenticated user trying to update dosen profile');
                return $this->sendResponse(false, 'Sesi login telah berakhir. Silakan login kembali.', 401);
            }

            // Enhanced validation
            $validatedData = $request->validate([
                'Nama' => 'required|string|max:100',
                'Alamat' => 'required|string|max:255',
                'Nohp' => 'required|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'Nama.required' => 'Nama harus diisi',
                'Nama.max' => 'Nama maksimal 100 karakter',
                'Alamat.required' => 'Alamat harus diisi',
                'Alamat.max' => 'Alamat maksimal 255 karakter',
                'Nohp.required' => 'Nomor HP harus diisi',
                'Nohp.max' => 'Nomor HP maksimal 20 karakter',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
                'profile_photo.image' => 'File harus berupa gambar',
                'profile_photo.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
                'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            DB::beginTransaction();

            $updateData = [
                'Nama' => $validatedData['Nama'],
                'Alamat' => $validatedData['Alamat'],
                'Nohp' => $validatedData['Nohp'],
            ];

            $newPhotoUrl = null;

            // Handle password update
            if (!empty($validatedData['password'])) {
                $updateData['password'] = Hash::make($validatedData['password']);
                Log::info('Password updated for dosen: ' . $dosen->NIP);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                try {
                    $this->ensureDirectoryExists();
                    
                    $file = $request->file('profile_photo');
                    
                    // Validate file
                    if (!$file->isValid()) {
                        throw new Exception('File upload tidak valid.');
                    }
                    
                    // Delete old photo if exists
                    if ($dosen->profile_photo) {
                        $this->imageService->deleteProfilePhoto($dosen->profile_photo);
                    }
                    
                    // Generate unique filename
                    $fileName = 'dosen_' . $dosen->NIP . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Resize and save the image
                    $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
                    
                    $updateData['profile_photo'] = $savedFileName;
                    $newPhotoUrl = asset('storage/profile_photos/' . $savedFileName);
                    
                    Log::info('Profile photo uploaded successfully for dosen: ' . $dosen->NIP, [
                        'file_name' => $savedFileName
                    ]);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to upload profile photo for dosen: ' . $dosen->NIP, [
                        'error' => $e->getMessage()
                    ]);
                    
                    return $this->sendResponse(false, 'Gagal mengupload foto: ' . $e->getMessage());
                }
            }

            // Update dosen data
            $updated = Dosen::where('NIP', $dosen->NIP)->update($updateData);
            
            if (!$updated) {
                throw new Exception('Gagal memperbarui data dosen di database');
            }

            DB::commit();
            
            Log::info('Profile updated successfully for dosen: ' . $dosen->NIP);

            return $this->sendResponse(true, 'Profile berhasil diperbarui!', 200, [
                'profile_photo_url' => $newPhotoUrl,
                'user_name' => $updateData['Nama']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $this->sendResponse(false, 'Validasi gagal', 422, null, $e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating dosen profile: ' . $e->getMessage(), [
                'dosen_nip' => $dosen->NIP ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->sendResponse(false, 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage());
        }
    }

    /**
     * Show profile edit form for mahasiswa
     */
    public function editMahasiswa()
    {
        try {
            $mahasiswa = Auth::guard('mahasiswa')->user();
            
            if (!$mahasiswa) {
                Log::warning('Unauthenticated user trying to access mahasiswa profile edit');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $golongan = Golongan::all();
            return view('profile.edit-mahasiswa', compact('mahasiswa', 'golongan'));
        } catch (Exception $e) {
            Log::error('Error accessing mahasiswa profile edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengakses halaman profile.');
        }
    }

    /**
     * Update mahasiswa profile
     */
    public function updateMahasiswa(Request $request)
    {
        try {
            $mahasiswa = Auth::guard('mahasiswa')->user();
            
            if (!$mahasiswa) {
                Log::warning('Unauthenticated user trying to update mahasiswa profile');
                return $this->sendResponse(false, 'Sesi login telah berakhir. Silakan login kembali.', 401);
            }

            // Enhanced validation for mahasiswa
            $validatedData = $request->validate([
                'Nama' => 'required|string|max:100',
                'Alamat' => 'required|string|max:255',
                'Nohp' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'Nama.required' => 'Nama harus diisi',
                'Nama.max' => 'Nama maksimal 100 karakter',
                'Alamat.required' => 'Alamat harus diisi',
                'Alamat.max' => 'Alamat maksimal 255 karakter',
                'Nohp.max' => 'Nomor HP maksimal 20 karakter',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
                'profile_photo.image' => 'File harus berupa gambar',
                'profile_photo.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
                'profile_photo.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            DB::beginTransaction();

            $updateData = [
                'Nama' => $validatedData['Nama'],
                'Alamat' => $validatedData['Alamat'],
                'Nohp' => $validatedData['Nohp'] ?? null,
            ];

            $newPhotoUrl = null;

            // Handle password update
            if (!empty($validatedData['password'])) {
                $updateData['password'] = Hash::make($validatedData['password']);
                Log::info('Password updated for mahasiswa: ' . $mahasiswa->NIM);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                try {
                    $this->ensureDirectoryExists();
                    
                    $file = $request->file('profile_photo');
                    
                    // Validate file
                    if (!$file->isValid()) {
                        throw new Exception('File upload tidak valid.');
                    }
                    
                    // Delete old photo if exists
                    if ($mahasiswa->profile_photo) {
                        $this->imageService->deleteProfilePhoto($mahasiswa->profile_photo);
                    }
                    
                    // Generate unique filename
                    $fileName = 'mahasiswa_' . $mahasiswa->NIM . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Resize and save the image
                    $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
                    
                    $updateData['profile_photo'] = $savedFileName;
                    $newPhotoUrl = asset('storage/profile_photos/' . $savedFileName);
                    
                    Log::info('Profile photo uploaded successfully for mahasiswa: ' . $mahasiswa->NIM, [
                        'file_name' => $savedFileName
                    ]);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to upload profile photo for mahasiswa: ' . $mahasiswa->NIM, [
                        'error' => $e->getMessage()
                    ]);
                    
                    return $this->sendResponse(false, 'Gagal mengupload foto: ' . $e->getMessage());
                }
            }

            // Update mahasiswa data
            $updated = Mahasiswa::where('NIM', $mahasiswa->NIM)->update($updateData);
            
            if (!$updated) {
                throw new Exception('Gagal memperbarui data mahasiswa di database');
            }

            DB::commit();
            
            Log::info('Profile updated successfully for mahasiswa: ' . $mahasiswa->NIM);

            return $this->sendResponse(true, 'Profile berhasil diperbarui!', 200, [
                'profile_photo_url' => $newPhotoUrl,
                'user_name' => $updateData['Nama']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $this->sendResponse(false, 'Validasi gagal', 422, null, $e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating mahasiswa profile: ' . $e->getMessage(), [
                'mahasiswa_nim' => $mahasiswa->NIM ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->sendResponse(false, 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage());
        }
    }

    /**
     * Upload profile photo specifically
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'user_type' => 'required|in:dosen,mahasiswa'
            ]);

            $userType = $request->input('user_type');
            
            if ($userType === 'dosen') {
                $user = Auth::guard('dosen')->user();
                $prefix = 'dosen_' . $user->NIP;
            } elseif ($userType === 'mahasiswa') {
                $user = Auth::guard('mahasiswa')->user();
                $prefix = 'mahasiswa_' . $user->NIM;
            } else {
                return $this->sendResponse(false, 'Tipe user tidak valid');
            }

            if (!$user) {
                return $this->sendResponse(false, 'User tidak ditemukan', 401);
            }

            DB::beginTransaction();

            // Ensure directory exists
            $this->ensureDirectoryExists();
            
            $file = $request->file('profile_photo');
            if (!$file->isValid()) {
                throw new Exception('File upload tidak valid.');
            }

            // Delete old photo if exists
            if ($user->profile_photo) {
                $this->imageService->deleteProfilePhoto($user->profile_photo);
            }
            
            // Generate unique filename
            $fileName = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Resize and save the image
            $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
            
            // Update user profile_photo
            if ($userType === 'dosen') {
                Dosen::where('NIP', $user->NIP)->update(['profile_photo' => $savedFileName]);
            } else {
                Mahasiswa::where('NIM', $user->NIM)->update(['profile_photo' => $savedFileName]);
            }
            
            $photoUrl = asset('storage/profile_photos/' . $savedFileName);
            
            DB::commit();
            
            Log::info('Profile photo uploaded via dedicated endpoint', [
                'user_type' => $userType,
                'user_id' => $userType === 'dosen' ? $user->NIP : $user->NIM,
                'file_name' => $savedFileName
            ]);
            
            return $this->sendResponse(true, 'Foto profil berhasil diupload!', 200, [
                'profile_photo_url' => $photoUrl
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $this->sendResponse(false, 'Validasi gagal', 422, null, $e->errors());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error uploading profile photo: ' . $e->getMessage());
            return $this->sendResponse(false, 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto(Request $request)
    {
        try {
            $userType = $request->input('user_type');
            
            if ($userType === 'dosen') {
                $user = Auth::guard('dosen')->user();
            } elseif ($userType === 'mahasiswa') {
                $user = Auth::guard('mahasiswa')->user();
            } else {
                return $this->sendResponse(false, 'Tipe user tidak valid');
            }

            if (!$user) {
                return $this->sendResponse(false, 'User tidak ditemukan', 401);
            }

            DB::beginTransaction();

            if ($user->profile_photo && $this->imageService->deleteProfilePhoto($user->profile_photo)) {
                if ($userType === 'dosen') {
                    Dosen::where('NIP', $user->NIP)->update(['profile_photo' => null]);
                } else {
                    Mahasiswa::where('NIM', $user->NIM)->update(['profile_photo' => null]);
                }
                
                DB::commit();
                
                Log::info('Profile photo deleted successfully', [
                    'user_type' => $userType,
                    'user_id' => $userType === 'dosen' ? $user->NIP : $user->NIM
                ]);
                
                return $this->sendResponse(true, 'Foto profil berhasil dihapus');
            }

            DB::rollBack();
            return $this->sendResponse(false, 'Foto profil tidak ditemukan atau gagal dihapus');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting profile photo: ' . $e->getMessage());
            return $this->sendResponse(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get current profile photo
     */
    public function getProfilePhoto(Request $request)
    {
        try {
            $userType = $request->input('user_type');
            
            if ($userType === 'dosen') {
                $user = Auth::guard('dosen')->user();
            } elseif ($userType === 'mahasiswa') {
                $user = Auth::guard('mahasiswa')->user();
            } else {
                return $this->sendResponse(false, 'Tipe user tidak valid');
            }

            if (!$user) {
                return $this->sendResponse(false, 'User tidak ditemukan', 401);
            }

            $photoUrl = null;
            if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                $photoUrl = asset('storage/profile_photos/' . $user->profile_photo);
            }

            return $this->sendResponse(true, 'Profile photo retrieved', 200, [
                'profile_photo_url' => $photoUrl,
                'has_photo' => !is_null($photoUrl)
            ]);

        } catch (Exception $e) {
            Log::error('Error getting profile photo: ' . $e->getMessage());
            return $this->sendResponse(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Ensure the profile photos directory exists
     */
    private function ensureDirectoryExists()
    {
        $directory = storage_path('app/public/profile_photos');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
            Log::info('Created profile_photos directory: ' . $directory);
        }
        
        // Also ensure proper permissions
        if (!is_writable($directory)) {
            chmod($directory, 0755);
            Log::info('Fixed permissions for profile_photos directory: ' . $directory);
        }
        
        // Ensure the public/storage symlink exists
        $publicStorageLink = public_path('storage');
        if (!file_exists($publicStorageLink)) {
            $storagePath = storage_path('app/public');
            if (is_dir($storagePath)) {
                symlink($storagePath, $publicStorageLink);
                Log::info('Created storage symlink: ' . $publicStorageLink);
            }
        }
    }

    /**
     * Send unified response
     */
    private function sendResponse($success, $message, $statusCode = 200, $data = null, $errors = null)
    {
        $response = [
            'success' => $success,
            'message' => $message
        ];

        if ($data) {
            $response = array_merge($response, $data);
        }

        if ($errors) {
            $response['errors'] = $errors;
        }

        if (request()->ajax()) {
            return response()->json($response, $statusCode);
        }

        if ($success) {
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $message);
        }
    }
}