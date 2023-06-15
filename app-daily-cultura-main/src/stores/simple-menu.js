import { defineStore } from "pinia";

export const useSimpleMenuStore = defineStore("simpleMenu", {
  state: () => ({
    menu: [
      {
        icon: "LayersIcon",
        pageName: "reports.index",
        title: "Reportes",
        ignore: true,
      },
      {

        icon: "SettingsIcon",
        pageName: "Configuración",
        title: "Configuración",
        subMenu: [
          {
            icon: "ListIcon",
            pageName: "Listas desplegables",
            title: "Listas desplegables",
            subMenu: [
              {
                icon: "MinusIcon",
                pageName: "culturals.index",
                title: "Derecho cultural",
                ignore: true,
              },
              {
                icon: "MinusIcon",
                pageName: "entities.index",
                title: "Entidades",
                ignore: true,
              },
              {
                icon: "MinusIcon",
                pageName: "expertises.index",
                title: "Experticia",
                ignore: true,
              },
              {
                icon: "MinusIcon",
                pageName: "nacs.index",
                title: "Nac",
                ignore: true,
              },
              {
                icon: "MinusIcon",
                pageName: "neighborhoods.index",
                title: "Barrio",
                ignore: true,
              },
              {
                icon: "MinusIcon",
                pageName: "orientations.index",
                title: "Orientación",
                ignore: true,
              },
            ],
          },
          {
            icon: "UserIcon",
            pageName: "Seguridad",
            title: "Seguridad",
            subMenu: [
              {
                icon: "ListIcon",
                pageName: "users.index",
                title: "Usuarios",
                ignore: true,
              },
              {
                icon: "ListIcon",
                pageName: "modules.index",
                title: "Modulo",
                ignore: true,
              },
              {
                icon: "ListIcon",
                pageName: "items.index",
                title: "Modulo item",
                ignore: true,
              },
              {
                icon: "ListIcon",
                pageName: "roles.index",
                title: "Roles",
                ignore: true,
              },
              {
                icon: "ListIcon",
                pageName: "permissions.index",
                title: "Permisos",
                ignore: true,
              },
            ]
          },
        ]
      
      },
      {
        icon: "LayersIcon",
        pageName: "characterization.index",
        title: "Encuesta",
        ignore: true,
        
      },
      {
        icon: "UsersIcon",
        pageName: "Monitores",
        title: "Monitores",
        subMenu: [
          {
            icon: "HomeIcon",
            pageName: "inscriptions.index",
            title: "Inscripciones",
            ignore: true,
          },
          {
            icon: "FileTextIcon",
            pageName: "pedagogicals.index",
            title: "Ficha Pedagógica",
            ignore: true,
          },
          {
            icon: "FileTextIcon",
            pageName: "binnacles.index",
            title: "Bitacoras",
            ignore: true,
          },
          {
            icon: "HomeIcon",
            pageName: "pecs.index",
            title: "Pecs",
          },
        ],
      },
      {
        icon: "UsersIcon",
        pageName: "Gestores",
        title: "Gestores",
        subMenu: [
          {
            icon: "FolderIcon",
            pageName: "dialoguetables.index",
            title: "Mesa de Dialogo",
          },
          {
            icon: "FolderIcon",
            pageName: "methodological.index",
            title: "Instruccion Metodologica",
          },
          {
            icon: "FolderIcon",
            pageName: "managermonitoring.index", //Pendiente definir ruta
            title: "Seg de gestor cultural", //
          },
          // {
          //   icon: "FileTextIcon",
          //   // pageName: "bitacoras.index", //Pendiente definir ruta
          //   title: "Bitacoras",
          //   ignore: true,
          // },
        ],
      },
    ],
  }),
});
