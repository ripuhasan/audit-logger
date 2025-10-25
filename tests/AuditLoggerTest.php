<?php

namespace MahedulHasan\AuditLogger\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MahedulHasan\AuditLogger\AuditLoggerServiceProvider;
use MahedulHasan\AuditLogger\Models\AuditLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class AuditLoggerTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [AuditLoggerServiceProvider::class];
    }

    /** @test */
    public function it_creates_an_audit_log_when_model_is_created()
    {
        // Prepare a dummy model table
        Schema::create('dummy_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Define a dummy model
        $model = new class extends Model {
            protected $table = 'dummy_models';
            protected $guarded = [];
        };

        // Observe the model manually (since auto-observe won’t run in Testbench)
        $model::observe(\MahedulHasan\AuditLogger\Observers\AuditObserver::class);

        // Create record
        $record = $model::create(['name' => 'Test Name']);

        // Assert audit log created
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'created',
            'model' => get_class($model),
            'model_id' => $record->id,
        ]);
    }
}