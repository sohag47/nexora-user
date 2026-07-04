<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_additional_infos', function (Blueprint $table) {
            $table->id();
            // Foreign Key
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Contact & Address Info
            $table->string('email_app_password')->nullable();
            $table->string('additional_phone_no', 50)->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('mailing_address')->nullable();
            $table->text('office_address')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_no', 50)->nullable();
            $table->string('emergency_contact_relation')->nullable();

            // Personal Info
            $table->string('blood_group', 10)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('marital_status', 50)->nullable();
            $table->string('disabilities')->nullable();

            // Family Info
            $table->string('fathers_name')->nullable();
            $table->string('fathers_nid_no', 50)->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('mothers_nid_no', 50)->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_nid_no', 50)->nullable();

            // Documents & Identifications (Assuming images are just file paths)
            $table->string('signature')->nullable();
            $table->string('nid_no', 50)->nullable();
            $table->string('nid_image')->nullable();
            $table->string('driving_license_no', 100)->nullable();
            $table->string('driving_license_image')->nullable();
            $table->string('birth_certificate_no', 100)->nullable();
            $table->string('birth_certificate_image')->nullable();
            $table->string('passport_no', 100)->nullable();
            $table->string('passport_image')->nullable();

            // Professional Info
            $table->string('profession')->nullable();
            $table->string('grade', 50)->nullable();
            $table->string('employee_code', 100)->nullable();
            $table->string('employee_id_card_image')->nullable();
            $table->dateTime('joining_date', 6)->nullable();
            $table->dateTime('expire_date', 6)->nullable();
            $table->dateTime('termination_date', 6)->nullable();
            $table->text('comment')->nullable();

            // Nominee Info
            $table->string('nominee_name')->nullable();
            $table->string('nominee_nid', 50)->nullable();
            $table->string('nominee_photo')->nullable();
            $table->string('nominee_phone_no', 50)->nullable();
            $table->text('nominee_address')->nullable();

            // Academic/Institution Info
            $table->string('institution_name')->nullable();
            $table->string('student_class', 100)->nullable();
            $table->string('student_id', 100)->nullable();
            $table->string('student_id_img')->nullable();

            // Cards & Hardware Info
            $table->string('seal')->nullable();
            $table->string('hf_card_no', 100)->nullable();
            $table->string('uhf_card_no', 100)->nullable();
            $table->integer('reporting_head_id')->nullable();
            $table->dateTime('validity_start_date', 6)->nullable();
            $table->dateTime('validity_end_date', 6)->nullable();
            $table->dateTime('card_printed_date', 6)->nullable();
            $table->integer('color')->default(0);
            $table->boolean('is_card_printed')->default(0);

            // Biometrics & Extra (Kept larger text formats just in case these are heavy data streams)
            $table->longText('finger_image')->nullable();
            $table->text('left_irish')->nullable();
            $table->text('right_irish')->nullable();
            $table->text('nid_input')->nullable();
            $table->longText('finger_binary')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_additional_infos');
    }
};
