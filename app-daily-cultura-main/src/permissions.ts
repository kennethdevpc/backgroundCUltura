import mixins from "./mixins";

const { is_role } = mixins.computed;

const super_admin = () => {
    return is_role("super.root") || is_role("root");
};

export default {
    // Monitors
    inscriptions: {
        create: () => {
            return (
                is_role("monitor_cultural") || is_role("instructor") || super_admin()
            );
        },
        management: () => {
            return is_role("apoyo_seguimiento_monitoreo") || super_admin();
        },
        crud_management: () => {
            return is_role("apoyo_seguimiento_monitoreo");
        },
        no_edit: () => {
            return is_role("apoyo_seguimiento_monitoreo");
        },
    },
    binnacles: {
        create: () => {
            return (
                is_role("monitor_cultural") ||
                is_role("instructor") ||
                is_role("embajador") ||
                super_admin()
            );
        },
        management: () => {
            return (
                is_role("apoyo_seguimiento_monitoreo") ||
                is_role("gestores_culturales") ||
                is_role("lider_instructor") ||
                is_role("lider_embajador") ||
                super_admin()
            );
        },
        crud_management: () => {
            return (
                is_role("apoyo_seguimiento_monitoreo") ||
                is_role("gestores_culturales") ||
                is_role("lider_instructor") ||
                is_role("lider_embajador")
            );
        },
        no_edit: () => {
            return (
                is_role("apoyo_seguimiento_monitoreo") ||
                is_role("lider_instructor") ||
                is_role("lider_embajador") ||
                is_role("gestores_culturales")
            );
        },
    },
    pecs: {
        create: () => {
            return (
                is_role("monitor_cultural") || is_role("instructor") || super_admin()
            );
        },
        management: () => {
            return (
                is_role("gestores_culturales") ||
                is_role("lider_instructor") ||
                super_admin()
            );
        },
        crud_management: () => {
            return is_role("gestores_culturales") || is_role("lider_instructor");
        },
        no_edit: () => {
            return is_role("gestores_culturales") || is_role("lider_instructor");
        },
    },
    pedagogicals: {
        create: () => {
            return (
                is_role("monitor_cultural") || is_role("instructor") || super_admin()
            );
        },
        management: () => {
            return (
                is_role("gestores_culturales") ||
                is_role("lider_instructor") ||
                super_admin()
            );
        },
        crud_management: () => {
            return is_role("gestores_culturales") || is_role("lider_instructor");
        },
        no_edit: () => {
            return is_role("gestores_culturales") || is_role("lider_instructor");
        },
    },
    // Gestors
    dialoguetables: {
        create: () => {
            return is_role("gestores_culturales") || super_admin();
        },
        management: () => {
            return is_role("apoyo_metodologico") || super_admin();
        },
        crud_management: () => {
            return is_role("apoyo_metodologico");
        },
        no_edit: () => {
            return is_role("apoyo_metodologico");
        },
    },
    binnaclesManagers: {
        create: () => {
            return is_role("gestores_culturales") || super_admin();
        },
        management: () => {
            return (
                is_role("apoyo_metodologico") ||
                is_role("apoyo_seguimiento_monitoreo") ||
                super_admin()
            );
        },
        crud_management: () => {
            return (
                is_role("apoyo_metodologico") || is_role("apoyo_seguimiento_monitoreo")
            );
        },
        no_edit: () => {
            return is_role("apoyo_metodologico") || is_role('apoyo_seguimiento_monitoreo');
        },
    },
    methodologicalInstructions: {
        create: () => {
            return is_role("gestores_culturales") || super_admin();
        },
        management: () => {
            return is_role("apoyo_metodologico") || super_admin();
        },
        crud_management: () => {
            return is_role("apoyo_metodologico");
        },
        no_edit: () => {
            return is_role("apoyo_metodologico");
        },
    },
    managermonitorings: {
        create: () => {
            return is_role("gestores_culturales") || super_admin();
        },
        management: () => {
            return is_role("apoyo_metodologico") || super_admin();
        },
        crud_management: () => {
            return is_role("apoyo_metodologico");
        },
        no_edit: () => {
            return is_role("apoyo_metodologico");
        },
    },
    // Psychosocial
    parentschools: {
        create: () => {
            return (
                is_role("psicosocial") ||
                is_role("coordinador_psicosocial") ||
                super_admin()
            );
        },
        management: () => {
            return is_role("coordinador_psicosocial") || super_admin();
        },
        crud_management: () => {
            return is_role("coordinador_psicosocial");
        },
        no_edit: () => {
            return is_role("coordinador_psicosocial");
        },
    },
    psychosocialinstructions: {
        create: () => {
            return (
                is_role("psicosocial") ||
                is_role("coordinador_psicosocial") ||
                super_admin()
            );
        },
        management: () => {
            return is_role("coordinador_psicosocial") || super_admin();
        },
        crud_management: () => {
            return is_role("coordinador_psicosocial");
        },
        no_edit: () => {
            return is_role("coordinador_psicosocial");
        },
    },
    psychopedagogicallogs: {
        create: () => {
            return (
                is_role("psicosocial") ||
                is_role("coordinador_psicosocial") ||
                super_admin()
            );
        },
        management: () => {
            return is_role("coordinador_psicosocial") || super_admin();
        },
        crud_management: () => {
            return is_role("coordinador_psicosocial");
        },
        no_edit: () => {
            return is_role("coordinador_psicosocial");
        },
    },
    coord_superv: {
        create: () => {
            return (
                is_role("coordinador_supervision") ||
                is_role("coordinador_seguimiento") ||
                is_role("coordinador_metodologico") ||
                is_role("coordinador_administrativo") ||
                is_role("coordinador_psicosocial") ||
                super_admin()
            );
        },
        management: () => {
            return is_role("subdireccion") || super_admin();
        },
        crud_management: () => {
            return is_role("subdireccion") || is_role("direccion");
        },
        no_edit: () => {
            return is_role("subdireccion");
        },
    },
    subdireccion: {
        create: () => { return (is_role('subdireccion') || super_admin()) },
        management: () => { return (is_role('subdireccion') || super_admin()) },
        crud_management: () => { return (is_role('subdireccion')) },
        no_edit: () => { return (is_role('subdireccion')) }
    },
    direccion: {
        create: () => { return (is_role('direccion') || super_admin()) },
        management: () => { return (is_role('direccion') || super_admin()) },
        crud_management: () => { return (super_admin()) || is_role('direccion') },
        crud_management_coordinador: () => { return (super_admin()) },
        no_edit: () => { return (is_role('direccion')) }
    },
    sheetsOne: {
        create: () => { return (is_role('instructor') || super_admin()) },
        management: () => { return (is_role('lider_instructor') || super_admin()) },
        crud_management: () => { return (is_role('lider_instructor') || super_admin()) },
        no_edit: () => { return (is_role('lider_instructor')) }
    },
    //Nuevo cambio
    create: () => {
        return is_role("subdireccion") || super_admin();
    },
    management: () => {
        return is_role("subdireccion") || super_admin();
    },
    crud_management: () => {
        return is_role("subdireccion");
    },
    no_edit: () => {
        return is_role("subdireccion");
    },
    culturalEnsembles: {
        create: () => {
            return (
                is_role("monitor_cultural") || is_role("instructor") || super_admin()
            );
        },
        management: () => {
            return (
                is_role("apoyo_seguimiento_monitoreo") ||
                is_role("lider_instructor") ||
                super_admin()
            );
        },
        crud_management: () => {
            return is_role("apoyo_seguimiento_monitoreo") || is_role("lider_instructor");
        },
        no_edit: () => {
            return is_role("gestores_culturales") || is_role("lider_instructor") || is_role("apoyo_seguimiento_monitoreo");
        },
    },
    culturalSeddbeds: {
        create: () => {
            return (
                is_role("instructor") || super_admin()
            );
        },
        management: () => {
            return (
                is_role("apoyo_seguimiento_monitoreo") ||
                is_role("lider_instructor") ||
                super_admin()
            );
        },
        crud_management: () => {
            return is_role("apoyo_seguimiento_monitoreo") || is_role("lider_instructor");
        },
        no_edit: () => {
            return is_role("apoyo_seguimiento_monitoreo") || is_role("lider_instructor");
        },
    },
    cultural_circulation: {
        create: () => { return (is_role('instructor') || super_admin()) },
        management: () => { return (is_role('lider_instructor') || is_role('apoyo_seguimiento_monitoreo') || super_admin()) },
        crud_management: () => { return (is_role('lider_instructor')) },
        no_edit: () => { return (is_role('lider_instructor') || is_role('apoyo_seguimiento_monitoreo')) }
    },
    binnacleculturalshow: {
        create: () => { return (is_role('embajador') || super_admin()) },
        management: () => { return (is_role('lider_embajador') || is_role('apoyo_seguimiento_monitoreo') || super_admin()) },
        crud_management: () => { return (is_role('embajador')) },
        no_edit: () => { return (is_role('lider_embajador') || is_role('apoyo_seguimiento_monitoreo')) }
    },
    instructor: {
        create: () => { return (is_role('instructor') || super_admin()) },
        management: () => { return (is_role('lider_instructor') || super_admin() || is_role('apoyo_seguimiento_monitoreo')) },
        crud_management: () => { return (is_role('lider_instructor') || is_role('apoyo_seguimiento_monitoreo')) },
        no_edit: () => { return (is_role('lider_instructor') || is_role('apoyo_seguimiento_monitoreo')) }
    },

    methodologicalAccompaniments: {
        create: () => { return (is_role('apoyo_metodologico') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_metodologico')) },
        crud_management: () => { return (is_role('coordinador_metodologico')) },  //is_role('apoyo_metodologico')
        no_edit: () => { return (is_role('coordinador_metodologico')) }
    },
    methodologicalStrengthenings: {
        create: () => { return (is_role('apoyo_metodologico') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_metodologico')) },
        crud_management: () => { return (is_role('coordinador_metodologico')) }, //is_role('apoyo_metodologico')
        no_edit: () => { return (is_role('coordinador_metodologico')) }
    },
    strengthening_monitorings: {
        create: () => { return (is_role('apoyo_seguimiento_monitoreo') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_seguimiento')) },
        crud_management: () => { return (is_role('coordinador_seguimiento')) },
        no_edit: () => { return (is_role('coordinador_seguimiento')) }
    },
    monthlyMonitoring: {
        create: () => { return (is_role('coordinador_supervision') || super_admin()) },
        management: () => { return (super_admin() || is_role('direccion')) },
        crud_management: () => { return (is_role('direccion')) },
        no_edit: () => { return (is_role('direccion')) }
    },
    monitoringReports: {
        create: () => { return (is_role('apoyo_seguimiento_monitoreo') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_seguimiento')) },
        crud_management: () => { return (is_role('coordinador_seguimiento')) },
        no_edit: () => { return (is_role('coordinador_seguimiento')) }
    },
    methodologicalMonitorings: {
        create: () => { return (is_role('lider_metodologico') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_metodologico')) },
        crud_management: () => { return (is_role('coordinador_metodologico')) },
        no_edit: () => { return (is_role('coordinador_metodologico')) }
    },
    strengtheningSuperMonIns: {
        create: () => { return (is_role('apoyo_supervision') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_supervision')) },
        crud_management: () => { return (is_role('coordinador_supervision')) },
        no_edit: () => { return (is_role('coordinador_supervision')) }
    },
    strengtheningSupervisionManager: {
        create: () => { return (is_role('apoyo_supervision') || super_admin()) },
        management: () => { return (super_admin() || is_role('coordinador_supervision')) },
        crud_management: () => { return (is_role('coordinador_supervision')) },
        no_edit: () => { return (is_role('coordinador_supervision')) }
    },
    // template: {
    //     create: () => { return (super_admin()) },
    //     management: () => { return (super_admin()) },
    // crud_management: () => { return (is_role('')) },
    //     no_edit: () => { return (super_admin()) }
    // }
};
