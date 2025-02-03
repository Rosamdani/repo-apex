<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => ['required', 'string', 'max:15', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/'],
            'universitas' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer|min:1900|max:2800',
            'status_pendidikan' => 'required|string|in:koas,pre-klinik',
            'semester' => 'nullable|required_if:status_pendidikan,pre-klinik|integer|min:1|max:20',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$#%^&*()_+=@]).*$/u',
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.alpha' => 'Nama hanya boleh mengandung huruf',
            'name.max' => 'Nama maksimal 255 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'email.max' => 'Email maksimal 255 karakter',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.max' => 'Nomor telepon maksimal 255 karakter',
            'no_telp.regex' => 'Nomor telepon tidak valid',
            'universitas.required' => 'Universitas harus diisi',
            'universitas.max' => 'Universitas maksimal 255 karakter',
            'tahun_masuk.required' => 'Tahun masuk harus diisi',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka',
            'tahun_masuk.min' => 'Tahun masuk minimal 1900',
            'tahun_masuk.max' => 'Tahun masuk maksimal 2800',
            'status_pendidikan.required' => 'Status pendidikan harus dipilih',
            'status_pendidikan.in' => 'Status pendidikan tidak valid',
            'semester.required_if' => 'Semester harus diisi jika status pendidikan adalah pre-klinik',
            'semester.integer' => 'Semester harus berupa angka',
            'semester.min' => 'Semester minimal 1',
            'semester.max' => 'Semester maksimal 20',
            'password.required' => 'Kata sandi harus diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.regex' => 'Kata sandi harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter spesial (e.g !$#%^&*()_+=@)',
            'password_confirmation.required' => 'Konfirmasi kata sandi harus diisi',
            'password_confirmation.same' => 'Konfirmasi kata sandi tidak sama',
        ];
    }
}
