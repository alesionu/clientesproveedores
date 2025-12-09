<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de Usuarios</h3>
        <div class="card-tools">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>public/usuarios/nuevo">
                <i class="fas fa-plus"></i> Nuevo usuario
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered" id="id_data_table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th class="no-export">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['usuario']; ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" 
                           href="<?php echo base_url(); ?>public/usuarios/editar/<?php echo $usuario['id']; ?>">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button class="btn btn-danger btn-sm btn-eliminar" 
                                data-url="<?= base_url('public/usuarios/borrar/' . $usuario['id']) ?>">
                                     <i class="fas fa-trash"></i> Borrar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>