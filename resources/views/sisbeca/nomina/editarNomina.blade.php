@extends('sisbeca.layouts.main')
@section('title','Editar Nómina '.$mes.'/'.$year)
@section('content')

<div class="col-lg-12" id="app">
<div class="d-flex justify-content-between">
<button v-if="itemsS.length" @click.prevent.stop="$refs.nominaGeneradaRef.show()" v-b-popover.hover="'Actualizar Nomina del '+mes+'/'+year" class="btn sisbeca-btn-primary">
		Actualizar Nomina @{{mes}}/@{{year}} <i class="fa fa-clone"></i>
	</button>
	<button @click="regresar" class="btn btn-sm sisbeca-btn-primary">Atrás</button>
</div>

<div class="mt-4" v-if="seccionS">

 <div class="accordion row" id="accordionExample">
		<div class="card col-12">
			<div id="heading" data-toggle="collapse" class="cursor" @click="collapse=!collapse" data-target="#collapseOne" aria-expanded="1" aria-controls="collapseOne">
					<h4 class="success">Listado de becarios encontrados en Nomina del @{{mes}}/@{{year}} <i v-if="!collapse" class="fa fa-chevron-down f-23"></i> <i v-else  class="fa fa-chevron-up f-23" ></i> </h4>
					<hr/>
			</div>
			<div id="collapseOne" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">

			<div class="table-responsive">
					<div class="dataTables_wrapper dt-bootstrap4 no-footer">
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="dataTables_length" style="">
									<label>Mostrar
										<select aria-controls="dd" v-model="perPageS" class="custom-select custom-select-sm form-control form-control-sm">
											<option v-for="(value, key) in pageOptionsS" :key="key">
												@{{value}}
											</option>
										</select> Entradas</label>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="dataTables_filter pull-right">
										<b-input-group-append>
											<label>Buscar<input type="search" v-model="filterS" class="form-control form-control-sm" placeholder="" >
											</label>
										</b-input-group-append>
									</div>
								</div>
							</div>
							<b-form-checkbox :disabled="!(itemsS.length)" class="ml-2" v-model="selectedAllS" id="sugeridos" @change="selectS" > <span v-if="!selectedAllS">Marcar Todos</span><span v-else>Desmarcar Todos</span></b-form-checkbox>
							<b-btn size="sm" :disabled="!itemsSC" class="float-right sisbeca-btn-primary" variant="sisbeca-btn-primary" @click="quitarSelectedsDeSugeridos"> Quitar Marcados de Nomina</b-btn>

							<b-table
							show-empty
							empty-text ="<span class='label label-default'>No se encontran becarios en esta nomina</span>"
							empty-filtered-text="
							No hay registros que coincidan con su búsqueda"
							class="table table-bordered table-hover dataTable no-footer"
							stacked="md"
							:items="itemsSComputed"
							:fields="fieldsS"
							:current-page="currentPageS"
							:per-page="parseInt(perPageS)"
							:filter="filterS"
							:sort-by.sync="sortByS"
							:sort-desc.sync="sortDescS"
							:sort-direction="sortDirectionS"
							@filtered="onFilteredS"
							>
							<template slot="selected" slot-scope="row">
									<b-form-checkbox v-model="row.item.selected"></b-form-checkbox>
							</template>
							<template slot="status" slot-scope="row">
								<span v-if="row.item.status_becario=='probatorio1'" class="label label-warning"> En Probatorio 1</span>
								<span v-else-if="row.item.status_becario=='activo'" class="label label-success">Activo</span>
								<span v-else-if="row.item.status_becario=='egresado'" class="label label-info">En Probatorio 2</span>
								<span v-else-if="row.item.status_becario=='probatorio2'" class="label label-danger">Egresado</span>
							</template>
							<template slot="becario" slot-scope="row">
								<span>@{{row.item.nomina.datos_nombres+' '+row.item.nomina.datos_apellidos}}</span><br/><span>CI:@{{row.item.nomina.datos_cedula}}</span>
							</template>

							<template slot="estipendio" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.sueldo_base)}}</span>
								</a>
							</template>
							<template slot="retroactivo" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.retroactivo)}}</span> <a v-b-popover.hover.bottom="'Editar Retroactivo'" @click.stop="editRetroactivo(row.item, row.index, $event.target)" class="btn btn-xs sisbeca-btn-primary">
										<i class="fa fa-pencil"></i>
								</a>
							</template>
							<template slot="cva" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.cva)}}</span> <a v-b-popover.hover.bottom="'Editar CVA'" @click.stop="editCVA(row.item, row.index, $event.target)" class="btn btn-xs sisbeca-btn-primary">
										<i class="fa fa-pencil"></i>
								</a>
							</template>
							<template slot="libros" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.monto_libros)}}</span>
								<a v-b-popover.hover.bottom="'Procesar Facturas'" @click.stop="editFacturas(row.item, row.index, $event.target)" class="btn btn-xs sisbeca-btn-primary">
										<i class="fa fa-pencil"></i>
								</a>

							</template>
							<template slot="total" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.total)}}</span>
							</template>

							<template slot="actions" slot-scope="row">
									<a v-b-popover.hover.bottom="'Detalles'" @click.stop="row.toggleDetails"class="btn btn-xs sisbeca-btn-primary">
										<i class="fa fa-eye" v-if="!row.detailsShowing"></i> <i v-else class=" fa fa-eye-slash"></i>
									</a>
									<a v-b-popover.hover.bottom="'Quitar de Sugeridos'" @click.stop="quitarDeSugeridos(row.item, row.index,true)" class="btn btn-xs sisbeca-btn-primary">
											<i class="fa fa-minus"></i>
									</a>

								</template>
								<template slot="row-details" slot-scope="row">
									<div class="table-responsive">

										<!-- data-order='[[ 5, "asc" ],[2,"desc"],[0,"asc"]]'-->
										<table  class="table striped stacked" >
											<thead>
												<tr>
													<th class="text-center">Datos Becario</th>
													<th class="text-center">Estatus</th>
													<th class="text-center">Cuenta Bancaria</th>
													<th class="text-center">Beca Aprobada</th>
													<th class="text-center">Ingreso</th>
													<th class="text-center">Egreso</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="text-center"> @{{row.item.nomina.datos_nombres }} @{{row.item.nomina.datos_apellidos }}<br/>@{{row.item.nomina.datos_email}}<br/>@{{row.item.nomina.datos_cedula}}<br/> </td>
													<td class="text-center">
															<span v-if="row.item.status_becario=='probatorio1'" class="label label-warning"> En Probatorio 1</span>
															<span v-else-if="row.item.status_becario=='activo'" class="label label-success">Activo</span>
															<span v-else-if="row.item.status_becario=='egresado'" class="label label-info">En Probatorio 2</span>
															<span v-else-if="row.item.status_becario=='probatorio2'" class="label label-danger">Egresado</span>
														</td>
													<td class="text-center"><span>@{{row.item.nomina.datos_cuenta}}</span>
													</td>

													<td class="text-center">
															<span v-if="row.item.fecha_ingreso">@{{ fechaformatear(row.item.fecha_ingreso) }}</span>
															<span v-else class='label label-default'>Sin Fecha Ingreso</span>
													</td>
													<td class="text-center">
															<span v-if="row.item.fecha_bienvenida">@{{ fechaformatear(row.item.fecha_bienvenida) }}</span>
															<span v-else class='label label-default'>Sin Fecha Bienvenida</span>
													</td>
													<td class="text-center">
														<span v-if="row.item.final_carga_academica">@{{ fechaformatear(row.item.final_carga_academica) }}</span>
														<span v-else-if="obtenerFechaEgreso(row.item.fecha_ingreso) !== '-'">@{{obtenerFechaEgreso(row.item.fecha_ingreso)}}</span>
														<span v-else class='label label-default'>Sin Fecha Egreso</span>
													</td>


												</tr>
											</tbody>

										</table>
									</div>
								<hr/>
								  </template>

						</b-table>

						<b-row class="my-0 pull-right" >
							<b-pagination :total-rows="totalRowsS" :per-page="parseInt(perPageS)" v-model="currentPageS" class="my-0" />
						</b-row>


					</div>
				</div>


			</div>
		</div>
	</div>
	<div class="accordion row" id="accordionExample2">
		<div class="card col-12">
			<div id="heading2" data-toggle="collapse" class="cursor" @click="collapse2=!collapse2" data-target="#collapseTwo" aria-expanded="2" aria-controls="collapseTwo">
					<h4 class="warning">Listado de becarios no encontrados en Nomina del @{{mes}}/@{{year}}  <i v-if="!collapse2" class="fa fa-chevron-down f-23"></i> <i v-else  class="fa fa-chevron-up f-23" ></i></h4>
					<hr/>
			</div>
			<div id="collapseTwo" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample2">

			<div class="table-responsive">
					<div class="dataTables_wrapper dt-bootstrap4 no-footer">
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="dataTables_length" style="">
									<label>Mostrar
										<select aria-controls="dd" v-model="perPageNS" class="custom-select custom-select-sm form-control form-control-sm">
											<option v-for="(value, key) in pageOptionsNS" :key="key">
												@{{value}}
											</option>
										</select> Entradas</label>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="dataTables_filter pull-right">
										<b-input-group-append>
											<label>Buscar<input type="search" v-model="filterNS" class="form-control form-control-sm" placeholder="" >
											</label>
										</b-input-group-append>
									</div>
								</div>
							</div>
							<b-form-checkbox :disabled="!(itemsNS.length)" class="ml-2" v-model="selectedAllNS" @change="selectNS" > <span v-if="!selectedAllNS">Marcar Todos</span><span v-else>Desmarcar Todos</span></b-form-checkbox>
							<b-btn :disabled="!itemsNSC"  size="sm" class="float-right sisbeca-btn-primary" @click="agregarSelectedsASugeridos" variant="sisbeca-btn-primary" > Agregar Marcados a Nomina</b-btn>

							<b-table
							show-empty
							empty-text ="<span class='label label-default'>Sin becarios encontrados</span>"
							empty-filtered-text="
							No hay registros que coincidan con su búsqueda"
							class="table table-bordered table-hover dataTable no-footer"
							stacked="md"
							:items="itemsNSComputed"
							:fields="fieldsNS"
							:current-page="currentPageNS"
							:per-page="parseInt(perPageNS)"
							:filter="filterNS"
							:sort-by.sync="sortByNS"
							:sort-desc.sync="sortDescNS"
							:sort-direction="sortDirectionNS"
							@filtered="onFilteredNS"
							>
							<template slot="selected" slot-scope="row">
									<b-form-checkbox v-model="row.item.selected"></b-form-checkbox>
							</template>
							<template slot="status" slot-scope="row">
								<span v-if="row.item.status_becario=='probatorio1'" class="label label-warning"> En Probatorio 1</span>
								<span v-else-if="row.item.status_becario=='activo'" class="label label-success">Activo</span>
								<span v-else-if="row.item.status_becario=='egresado'" class="label label-info">En Probatorio 2</span>
								<span v-else-if="row.item.status_becario=='probatorio2'" class="label label-danger">Egresado</span>
							</template>
							<template slot="becario" slot-scope="row">
								<span>@{{row.item.nomina.datos_nombres+' '+row.item.nomina.datos_apellidos}}</span><br/><span>CI:@{{row.item.nomina.datos_cedula}}</span>
							</template>
							<template slot="estipendio" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.sueldo_base)}}</span>
							</template>
							<template slot="retroactivo" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.retroactivo)}}</span>
							</template>
							<template slot="cva" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.cva)}}</span>
							</template>
							<template slot="libros" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.monto_libros)}}</span>
							</template>
							<template slot="total" slot-scope="row">
								<span>@{{formatomoneda(row.item.nomina.total)}}</span>
							</template>

							<template slot="actions" slot-scope="row">
								<a v-b-popover.hover.bottom="'Detalles'" @click.stop="row.toggleDetails"class="btn btn-xs sisbeca-btn-primary">
									<i class="fa fa-eye" v-if="!row.detailsShowing"></i> <i v-else class="fa fa-eye-slash"></i>
								</a>
								<a v-b-popover.hover.bottom="'Agregar a Sugeridos'"@click.stop="agregarASugeridos(row.item, row.index,true)" class="btn btn-xs sisbeca-btn-primary">
										<i class="fa fa-plus"></i>
								</a>


							</template>
							<template slot="row-details" slot-scope="row">

								<div class="table-responsive">

									<!-- data-order='[[ 5, "asc" ],[2,"desc"],[0,"asc"]]'-->
									<table  class="table stacked striped " >
										<thead>
											<tr>
												<th class="text-center">Becario</th>
												<th class="text-center">Estatus</th>
												<th class="text-center">Cuenta Bancaria</th>
												<th class="text-center">Beca Aprobada</th>
												<th class="text-center">Ingreso</th>
												<th class="text-center">Egreso</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-center"> @{{row.item.nomina.datos_nombres }} @{{row.item.nomina.datos_apellidos }}<br/>@{{row.item.nomina.datos_email}}<br/>@{{row.item.nomina.datos_cedula}}<br/> </td>
												<td class="text-center">
														<span v-if="row.item.status_becario=='probatorio1'" class="label label-warning"> En Probatorio 1</span>
														<span v-else-if="row.item.status_becario=='activo'" class="label label-success">Activo</span>
														<span v-else-if="row.item.status_becario=='egresado'" class="label label-info">En Probatorio 2</span>
														<span v-else-if="row.item.status_becario=='probatorio2'" class="label label-danger">Egresado</span>
													</td>
												<td class="text-center"><span>@{{row.item.nomina.datos_cuenta}}</span>
												</td>

												<td class="text-center">
														<span v-if="row.item.fecha_ingreso">@{{ fechaformatear(row.item.fecha_ingreso) }}</span>
														<span v-else class='label label-default'>Sin Fecha Ingreso</span>
												</td>
												<td class="text-center">
														<span v-if="row.item.fecha_bienvenida">@{{ fechaformatear(row.item.fecha_bienvenida) }}</span>
														<span v-else class='label label-default'>Sin Fecha Bienvenida</span>
												</td>
												<td class="text-center">
													<span v-if="row.item.final_carga_academica">@{{ fechaformatear(row.item.final_carga_academica) }}</span>
													<span v-else-if="obtenerFechaEgreso(row.item.fecha_ingreso) !== '-'">@{{obtenerFechaEgreso(row.item.fecha_ingreso)}}</span>
													<span v-else class='label label-default'>Sin Fecha Egreso</span>
												</td>


											</tr>
										</tbody>

									</table>
								</div>
								<hr/>

							  </template>

						</b-table>

						<b-row class="my-0 pull-right" >
							<b-pagination :total-rows="totalRowsNS" :per-page="parseInt(perPageNS)" v-model="currentPageNS" class="my-0" />
						</b-row>

					</div>
				</div>



			</div>

		</div>
	</div>

	<!-- Info modal -->
				<b-modal id="modalEditRetroactivo" hide-header-close ref='modalRetroactivo' @hide="resetModalEditRectroactivo" :title="'Editar Retroactivo'" >
								<div class="col" style="padding-left: 0px;padding-right: 0px;">
										<label class="control-label " for="retroactivo">Retroactivo</label>
										<input type="number" name="retroactivo" v-model="retroactivo"  class="sisbeca-input input-sm" id="retroactivo"  style="margin-bottom: 0px">
								</div>
						<template slot="modal-footer">
								<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.modalRetroactivo.hide()'> Cancelar</b-btn>
								<b-btn size="sm" class="float-right sisbeca-btn-primary" variant="sisbeca-btn-primary" @click='saveRetroactivo'> Guardar</b-btn>
						</template>
				</b-modal>

				<b-modal id="modalEditCVA" ref='modalCVA' hide-header-close @hide="resetModalEditCVA" :title="'Editar CVA'">
								<div class="col" style="padding-left: 0px;padding-right: 0px;">
											<label class="control-label " for="cva">CVA</label>
											<input type="number" name="cva" v-model="cva"  class="sisbeca-input input-sm" id="cva" style="margin-bottom: 0px">
									</div>
						<template slot="modal-footer">
								<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.modalCVA.hide()'> Cancelar</b-btn>
								<b-btn size="sm" class="float-right sisbeca-btn-primary" variant="sisbeca-btn-primary" @click='saveCVA'> Guardar</b-btn>
						</template>
				</b-modal>

				<b-modal id="modalEditFacturas" ref='modalFacturas' hide-header-close size="lg" @hide="resetModalEditFacturas" :title="'Procesar Facturas'">
						<div class="table-responsive" @click="selectedStatusFactura=-1">
								<div class="dataTables_wrapper dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12 col-md-6">
											<div class="dataTables_length" style="">
												<label>Mostrar
													<select aria-controls="dd" v-model="perPageF" class="custom-select custom-select-sm form-control form-control-sm">
														<option v-for="(value, key) in pageOptionsF"  :key="key">
															@{{value}}
														</option>
													</select> Entradas</label>
												</div>
											</div>
											<div class="col-sm-12 col-md-6">
												<div class="dataTables_filter pull-right">
													<b-input-group-append>
														<label>Buscar<input type="search" v-model="filterF" class="form-control form-control-sm" placeholder="" >
														</label>
													</b-input-group-append>
												</div>
											</div>
										</div>
										<b-form-checkbox :disabled="(typeof this.facturas === 'undefined') || !this.facturas.length" class="ml-2" v-model="selectedAllF" @change="selectF" > <span v-if="!selectedAllF">Marcar Todas</span><span v-else>Desmarcar Todas</span></b-form-checkbox>

										<b-table
										show-empty
										empty-text ="<span class='label label-default'>No existen Facturas cargadas del becario</span>"
										empty-filtered-text="
										No hay registros que coincidan con su búsqueda"
										class="table table-bordered table-hover dataTable no-footer"
										stacked="md"
										:items="facturas"
										:fields="fieldsF"
										:current-page="currentPageF"
										:per-page="parseInt(perPageF)"
										:filter="filterF"
										:sort-by.sync="sortByF"
										:sort-desc.sync="sortDescF"
										:sort-direction="sortDirectionF"
										@filtered="onFilteredF"
										>
										<template slot="selected" slot-scope="row">
												<b-form-checkbox v-model="row.item.selected" @change="selectedStatusFactura= -1"></b-form-checkbox>
										</template>
										<template slot="status" slot-scope="row" @click.prevent.stop="">
											<div @click.prevent.stop="">
											<div v-if="selectedStatusFactura==row.item.id" @click.prevent.stop="row.item.selected = true">
													<select v-model="row.item.factura.status" class="sisbeca-input">
															<option value="por procesar">Aprobada</option>
															<option value="rechazada">Rechazada</option>
															<option value="cargada">Pendiente</option>
														</select>
											</div>
											<div v-else>
													<span v-if="row.item.factura.status=='por procesar'" class="label label-warning"> Aprobada</span>
													<span v-else-if="row.item.factura.status=='procesada'" class="label label-success">Aprobada</span>
													<span v-else-if="row.item.factura.status=='cargada'" class="label label-info">Pendiente</span>
													<span v-else-if="row.item.factura.status=='rechazada'" class="label label-danger">Rechazada</span>
											</div>
											<a v-if="selectedStatusFactura==row.item.id" v-b-popover.hover.bottom="'Aceptar'" @click.prevent.stop="selectedStatusFactura=-1; row.item.selected = true" class="btn btn-xs sisbeca-btn-primary">
													<i class="fa fa-plus"></i>
											</a>
											<a v-else v-b-popover.hover.bottom="'Editar Estatus'" @click.prevent.stop="selectedStatusFactura=row.item.id;row.item.selected = true" class="btn btn-xs sisbeca-btn-primary">
													<i class="fa fa-pencil"></i>
											</a>
											</div>
										</template>
										<template slot="name" slot-scope="row">
											<span>@{{row.item.factura.name}}</span><br/><span>@{{row.item.factura.curso}}</span>
										</template>
										<template slot="costo" slot-scope="row">
											<span>@{{formatomoneda(row.item.factura.costo)}}</span>
										</template>

										<template slot="created_at" slot-scope="row">
												<span v-if="row.item.factura.created_at">@{{ fechaformatear(row.item.factura.created_at) }}</span>
												<span v-else class='label label-default'>Sin Fecha Carga</span>
										</template>

										<template slot="actions" slot-scope="row">
											<a v-b-popover.hover.bottom="'Ver Factura'" :href="row.item.link" target="_blank" class="btn btn-xs sisbeca-btn-primary">
												<i class="fa fa-eye"></i>
											</a>



										</template>

									</b-table>

									<b-row class="my-0 pull-right" >
										<b-pagination :total-rows="totalRowsF" :per-page="parseInt(perPageF)" v-model="currentPageF" class="my-0" />
									</b-row>

								</div>
							</div>
						<template slot="modal-footer">
								<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.modalFacturas.hide()'> Cancelar</b-btn>
								<b-btn :disabled="!facturasC"  size="sm" class="float-right sisbeca-btn-primary" @click="agregarSelectedsFacturas" variant="sisbeca-btn-primary" > Procesar Facturas Marcadas</b-btn>
						</template>
				</b-modal>



</div>
<section v-if="isLoading" class="loading" id="preloader">
      <div>
          <svg class="circular" viewBox="25 25 50 50">
              <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
      </div>
    </section>

		<b-modal id="nominaGeneradaID" hide-header-close ref="nominaGeneradaRef" :title="'Actualizar Nomina '+mes+'/'+year" >
	  	¿Estas Seguro que desea actualizar la Nomina correspondiente al @{{mes}}/@{{year}} con los registros seleccionados ?
	  <template slot="modal-footer">
				<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.nominaGeneradaRef.hide()'> No</b-btn>
				<b-btn  size="sm" class="float-right sisbeca-btn-primary" @click="generarNomina" variant="sisbeca-btn-primary" > Si </b-btn>
	   </template>	
    </b-modal>
</div>
@endsection

@section('personaljs')
<script>

$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	$("#dp3").datepicker( {
    format: "mm/yyyy",
    viewMode: "months",
    minViewMode: "months"
	});
});
</script>
<script>
mesEdit = "{{$mes}}"
yearEdit = "{{$year}}"
const app = new Vue({
	el: '#app',
	created: function()
	{
		this.consultar()
	},
	data:
	{
		showDate: false,
		collapse2: true,
		retroactivo: 0,
		idSelectedSugeridos: 0,
		selectedStatusFactura: -1,
		cva: 0,
		fecha: '',
		selectedAllS: false,
		collapse: true,
		isLoading: false,
		selectedAllNS: false,
		selectedAllF: false,
		itemsNS:
			[],
			fieldsNS: [
			{ key: 'selected', label: 'Marcar', sortable: true, 'class': 'text-center' },
			{ key: 'status', label: 'Estatus', sortable: true, 'class': 'text-center' },
			{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
			{ key: 'estipendio', label: 'Estipendio', sortable: true, 'class': 'text-center' },
			{ key: 'retroactivo', label: 'Retroactivo', sortable: true, 'class': 'text-center' },
			{ key: 'cva', label: 'CVA', sortable: true, 'class': 'text-center' },
			{ key: 'libros', label: 'Libros', sortable: true, 'class': 'text-center' },
			{ key: 'total', label: 'Total', sortable: true, 'class': 'text-center' },
			{ key: 'actions', label: 'Acciones', 'class': 'text-center' }
			],
			currentPageNS: 1,
			perPageNS: 10,
			totalRowsNS: 0,
			seccionS: false,
			pageOptionsNS: [ 10, 20,50,100 ],
			sortByNS: null,
			sortDescNS: false,
			sortDirectionNS: 'asc',
			filterNS: null,
			itemsS:
			[],
			fieldsS: [
			{ key: 'selected', label: 'Marcar', sortable: true, 'class': 'text-center' },
			{ key: 'status', label: 'Estatus', sortable: true, 'class': 'text-center' },
			{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
			{ key: 'estipendio', label: 'Estipendio', sortable: true, 'class': 'text-center' },
			{ key: 'retroactivo', label: 'Retroactivo', sortable: true, 'class': 'text-center' },
			{ key: 'cva', label: 'CVA', sortable: true, 'class': 'text-center' },
			{ key: 'libros', label: 'Libros', sortable: true, 'class': 'text-center' },
			{ key: 'total', label: 'Total', sortable: true, 'class': 'text-center' },
			{ key: 'actions', label: 'Acciones', 'class': 'text-center' }
			],
			currentPageS: 1,
			perPageS: 10,
			totalRowsS: 0,
			pageOptionsS: [ 10, 20,50,100 ],
			sortByS: null,
			sortDescS: false,
			sortDirectionS: 'asc',
			filterS: null,
			mes: mesEdit,
			year: yearEdit,
			facturas:
			[],
			fieldsF: [
			{ key: 'selected', label: 'Marcar', sortable: true, 'class': 'text-center' },
			{ key: 'status', label: 'Estatus', sortable: true, 'class': 'text-center' },
			{ key: 'name', label: 'Libro / Curso', sortable: true, 'class': 'text-center' },
			{ key: 'costo', label: 'Costo', sortable: true, 'class': 'text-center' },
			{ key: 'created_at', label: 'Fecha Cargada', sortable: true, 'class': 'text-center' },
			{ key: 'actions', label: 'Acciones', 'class': 'text-center' }
			],
			currentPageF: 1,
			perPageF: 5,
			totalRowsF: 0,
			pageOptionsF: [ 5, 20,50,100 ],
			sortByF: null,
			sortDescF: false,
			sortDirectionF: 'asc',
			filterF: null

	},
	computed:
		{
			itemsSComputed(){

				this.itemsS.forEach(function(e,i){
					e.nomina.total = parseFloat(e.nomina.monto_libros) + parseFloat(e.nomina.retroactivo) + parseFloat(e.nomina.sueldo_base) + parseFloat(e.nomina.cva)
				},this)
				return this.itemsS
			},
			itemsNSComputed(){

				this.itemsNS.forEach(function(e,i){
					e.nomina.total = parseFloat(e.nomina.monto_libros) + parseFloat(e.nomina.retroactivo) + parseFloat(e.nomina.sueldo_base) + parseFloat(e.nomina.cva)
				},this)
				return this.itemsNS
			}
			,
			sortOptionsNS ()
			{
				// Create an options list from our fields
				return this.fieldsNS
				.filter(f => f.sortable)
				.map(f => { return { text: f.label, value: f.key } })
			},
			sortOptionsS ()
			{
				// Create an options list from our fields
				return this.fieldsS
				.filter(f => f.sortable)
				.map(f => { return { text: f.label, value: f.key } })
			},
			sortOptionsF ()
			{
				// Create an options list from our fields
				return this.fieldsF
				.filter(f => f.sortable)
				.map(f => { return { text: f.label, value: f.key } })
			},
			itemsSC(){
				let existe = false
				this.itemsS.forEach(function(e,i){
					if(e.selected){
						existe = true
					}
				})
				return existe
			},
			itemsNSC(){
				let existe = false
				this.itemsNS.forEach(function(e,i){
					if(e.selected){
						existe = true
					}
				})
				return existe
			},
			facturasC(){
				let existe = false
				if((typeof this.facturas !== 'undefined') && this.facturas.length)
				{
					this.facturas.forEach(function(e,i){
					if(e.selected){
						existe = true
					}
				})
				}

				return existe
			}
		},
		methods:
		{
			generarNomina() {
				this.$refs.nominaGeneradaRef.hide()
				var url = "{{route('updateNomina.api')}}";
				this.isLoading = true
				let facturasAll = []

				let data = JSON.stringify({
                mes: parseInt(this.mes),
                year: parseInt(this.year),
								nomina: this.itemsS,
								nominaBorrador: this.itemsNS

            });
				axios.post(url,data,{
				headers:
				{
						'Content-Type': 'application/json',
				}
				}).then(response =>
				{
					console.log(response)
					if(response.data.res){
						this.seccionS = false
						this.itemsS = []
						this.itemsNS = []
						toastr.success('La nomina del '+this.mes+'/'+this.year+' fue actualizada exitosamente');
						let url = "{{route('nomina.listar')}}"
						location.replace(url)

					}else {
						toastr.warning('No existe una nomina para la fecha '+ this.fecha);

					}
					this.isLoading = false
				}).catch( error => {
						toastr.error('Ocurrio un error inesperado al generar nomina')
						this.isLoading = false
					});
			},
			saveRetroactivo(){
				this.itemsS.forEach(function(item,i){
					if(item.id === this.idSelectedSugeridos){
						item.nomina.retroactivo = parseFloat(this.retroactivo)
					}
				},this)
				this.$refs.modalRetroactivo.hide()
				toastr.info('Campo Editado Exitosamente');

				this.retroactivo = 0
			},
			saveCVA(){
				this.itemsS.forEach(function(item,i){
					if(item.id === this.idSelectedSugeridos){
						item.nomina.cva = parseFloat(this.cva)
					}
				},this)
				this.$refs.modalCVA.hide()
				toastr.info('Campo Editado Exitosamente');

				this.cva = 0
			},
			selectNS(){
				this.itemsNS.forEach(function(e,i){
					if(!this.selectedAllNS)
					{
						e.selected = true
					}else {
						e.selected = false
					}
				},this)
			},
			selectF(){
				this.facturas.forEach(function(e,i){
					if(!this.selectedAllF)
					{
						e.selected = true
					}else {
						e.selected = false
					}
				},this)
				this.selectedStatusFactura= -1
			},
			selectS(){
				this.itemsS.forEach(function(e,i){
					if(!this.selectedAllS)
					{
						e.selected = true
					}else {
						e.selected = false
					}
				},this)
			},
			editFacturas (item,index,button){
				this.idSelectedSugeridos = item.id
				this.facturas = []
				this.selectedAllF = false
				this.selectedStatusFactura = -1
				item.facturas.forEach(function(f,i){
					let url = "{{asset(':url_fact')}}"
					if(f.factura.url) {
						url = url.replace(':url_fact',f.factura.url.replace('/','',1))
					}
					let select = false
					if(f.factura.status !== 'cargada'){
						select = true
					}
					this.facturas.push({
						id: f.id,
						selected: select,
						link: url,
						factura: {...f.factura}
					})
				},this)
				this.$root.$emit('bv::show::modal', 'modalEditFacturas', button)
			},
			editRetroactivo (item, index, button) {
				this.retroactivo = item.nomina.retroactivo
				this.idSelectedSugeridos = item.id
				this.$root.$emit('bv::show::modal', 'modalEditRetroactivo', button)
			},
			editCVA (item, index, button) {
				this.cva = item.nomina.cva
				this.idSelectedSugeridos = item.id
				this.$root.$emit('bv::show::modal', 'modalEditCVA', button)
			},
			quitarSelectedsDeSugeridos(){
				let aux = []
				this.itemsS.forEach(function(e,i){
					aux.push({...e})
				})
				aux.forEach(function(e,i){
					if(e.selected){
						this.quitarDeSugeridos(e,i,false)
					}
				},this)
				this.selectedAllS = false
				toastr.info('Becarios seleccionados quitados de nomina');

			},
			quitarDeSugeridos (item, index, band) {
				/*this.modalInfoNS.title = `Row index: ${index}`
				this.modalInfoNS.content = JSON.stringify(item, null, 2)
				this.$root.$emit('bv::show::modal', 'modalInfoNS', button)
				*/
				let aux = {...item}
				aux.id =  this.itemsNS.length
				aux.selected = false
				aux._rowVariant = aux.is_sugerido ? 'success' : ''
				if( this.itemsNS.length)
				{
					aux.id =   this.itemsNS[this.itemsNS.length-1].id + 1

				}

	 			let id_eliminar = -1
				this.itemsS.forEach(function (elemento, indice) {
					if( elemento.id === item.id){
						id_eliminar = indice
					}
				},);
				if (id_eliminar !== -1){
					this.itemsS.splice(id_eliminar, 1);
					this.itemsNS.push(aux)
					if(band){
						toastr.info('Becario quitado de nomina');
					}
				}
			},
			regresar() {
				this.isLoading=true;
				var route= "{{route('nomina.listar')}}";
				location.assign(route);
			},
			agregarSelectedsASugeridos(){
				let aux = []
				this.itemsNS.forEach(function(e,i){
					aux.push({...e})
				})
				aux.forEach(function(e,i){
					if(e.selected){
						this.agregarASugeridos(e,i,false)
					}
				},this)
				this.selectedAllNS = false
				toastr.info('Becarios seleccionados agregados a nomina');

			},
			agregarSelectedsFacturas(){
				///
				this.itemsS.forEach(function(item,i){
					if(item.id === this.idSelectedSugeridos){
						//item.facturas= this.facturas
						let total = parseFloat(0)
						this.facturas.forEach(function(fact,i){
							if(fact.selected){
								if(fact.factura.status === 'por procesar'){
									total = parseFloat(total) + parseFloat(fact.factura.costo)
								}
								item.facturas[i] = fact
								//f.selected = false
							}
							fact.selected = false
						})
						/*
						let total = parseFloat(0)
						item.facturas.forEach(function(f,i){

							if(f.factura.status === 'procesada'){
								total = parseFloat(total) + parseFloat(f.factura.costo)
							}
							f.selected = false
						},this)
						*/
						item.nomina.monto_libros = parseFloat(total)
					}
				},this)


				this.$refs.modalFacturas.hide()
				this.facturas = []
				this.selectedAllF = false
				this.selectedStatusFactura = -1
				toastr.info('Factura(s) edita(s) ');

			},
			agregarASugeridos (item, index,band) {
				/*this.modalInfoNS.title = `Row index: ${index}`
				this.modalInfoNS.content = JSON.stringify(item, null, 2)
				this.$root.$emit('bv::show::modal', 'modalInfoNS', button)
				*/
				let aux = {...item}
				aux.id =  this.itemsS.length
				aux.selected = false
				aux._rowVariant = aux.is_sugerido ? '' : 'warning'
				if( this.itemsS.length)
				{
					aux.id =   this.itemsS[this.itemsS.length-1].id + 1

				}

	 			let id_eliminar = -1
				this.itemsNS.forEach(function (elemento, indice) {
					if( elemento.id === item.id){
						id_eliminar = indice
					}
				},);
				if (id_eliminar !== -1){
					this.itemsNS.splice(id_eliminar, 1);
					this.itemsS.push(aux)
					if(band)
					{
						toastr.info('Becario agregado a nomina');

					}
				}

			},
			resetModalEditRectroactivo () {
				this.retroactivo = 0
			},
			resetModalEditCVA () {
				this.cva = 0
			},
			resetModalEditFacturas () {
				this.facturas = []
				this.selectedAllF = false
				this.selectedStatusFactura = -1
			},
			fechaformatear(fecha)
			{
				if(fecha)
				{
					return moment(new Date (fecha)).format('DD/MM/YYYY')
				}
				return '-'
			},
			obtenerFechaEgreso(fecha){
				if(fecha) {
					let d = new Date (fecha)
					d.setFullYear(d.getFullYear() + 1)
					return moment(d).format('DD/MM/YYYY')
				}
				return '-'
			},
			formatomoneda(monto)
			{
					if(monto)
						return Number(monto).toLocaleString("es-ES", {minimumFractionDigits: 2});
					else
						return 0
			},
			onFilteredNS (filteredItems)
			{
				// Trigger pagination to update the number of buttons/pages due to filtering
				this.totalRowsNS = filteredItems.length
				this.currentPageNS = 1
			},
			onFilteredF (filteredItems)
			{
				// Trigger pagination to update the number of buttons/pages due to filtering
				this.totalRowsF = filteredItems.length
				this.currentPageF = 1
			},
			onFilteredS (filteredItems)
			{
				// Trigger pagination to update the number of buttons/pages due to filtering
				this.totalRowsS = filteredItems.length
				this.currentPageS = 1
			},
			consultar(){
				let url,mes,year
				mes = this.mes
				year = this.year
				url = "{{route('editar.becarios.nomina',array('mes'=>':a_mes','year'=>':b_year'))}}"
				url = url.replace(':a_mes',parseInt(mes))
				url = url.replace(':b_year',parseInt(year))
				this.isLoading = true
				axios.get(url).then(response =>
				{
					if(response.data.res){
						this.itemsNS = response.data.noSugeridos
						this.itemsS = response.data.sugeridos
						this.seccionS = true
					} else{
						toastr.warning('No existe nomina generada con los parametros solicitados');
						let url = "{{route('nomina.listar')}}"
						location.replace(url)
					}
					this.isLoading = false
				}).catch( error => {
					console.log(error);
					this.isLoading = false
					let url = "{{route('nomina.listar')}}"
					location.replace(url)
				});
		}
	}

});
</script>

@endsection


@section('personalcss')
<style scope="scss">
	.success {
		color: #28a745;
		font-weight: bold;
	}
	.warning {
		color: #ffc107;
		font-weight: bold;
	}
	.f-23{
		font-size:23px;
	}
	.cursor{
		cursor:pointer
	}
	.h-100{
		height: 100%
	}
	tbody tr td:last-child {
		text-align: center
	}
	.datepicker table tr td span.active.active,	.month.focused.active{
		background-color: #003865 !important;
		background-image: -webkit-gradient(linear,0 0,0 100%,from(#003865),to(#003865)) !important
	}



</style>
@endsection