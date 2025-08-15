<?php

namespace App\Events;

use App\Models\Matricula;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrematriculaSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matricula;

    /**
     * Create a new event instance.
     */
    public function __construct(Matricula $matricula)
    {
        $this->matricula = $matricula->load(['estudiante', 'apoderado', 'periodoAcademico']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        \Log::info('Broadcasting prematricula data:', [
            'matricula_id' => $this->matricula->id,
            'estudiante_id' => $this->matricula->estudiante_id,
            'estudiante_nombres' => $this->matricula->estudiante->nombres_estudiante ?? 'null',
            'estudiante_apellidos' => $this->matricula->estudiante->apellidos_estudiante ?? 'null',
            'estudiante_nombre_completo' => $this->matricula->estudiante->nombre_completo ?? 'null',
        ]);

        return [
            'id' => $this->matricula->id,
            'cod_matricula' => $this->matricula->cod_matricula,
            'estudiante' => [
                'nombre_completo' => $this->matricula->estudiante->nombre_completo ?? 'Sin nombre',
                'dni' => $this->matricula->estudiante->dni_estudiante ?? 'Sin DNI',
                'nombres' => $this->matricula->estudiante->nombres_estudiante ?? 'Sin nombres',
                'apellidos' => $this->matricula->estudiante->apellidos_estudiante ?? 'Sin apellidos',
            ],
            'apoderado' => [
                'nombre_completo' => $this->matricula->apoderado->nombre_completo ?? 'Sin nombre',
                'telefono' => $this->matricula->apoderado->telefono ?? 'Sin teléfono',
            ],
            'periodo' => $this->matricula->periodoAcademico->nombre ?? 'Sin período',
            'nivel' => ucfirst($this->matricula->nivel),
            'grado' => $this->matricula->grado,
            'created_at' => $this->matricula->created_at->format('H:i:s d/m/Y'),
            'timestamp' => now()->timestamp,
        ];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'prematricula.submitted';
    }
}
