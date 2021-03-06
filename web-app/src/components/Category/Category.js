import React, {Component} from 'react';
import PropTypes from 'prop-types';
import TableHead from "@material-ui/core/TableHead";
import Table from "@material-ui/core/Table";
import Paper from "@material-ui/core/Paper";
import TableRow from "@material-ui/core/TableRow";
import TableCell from "@material-ui/core/TableCell";
import TableBody from "@material-ui/core/TableBody";
import TablePagination from "@material-ui/core/TablePagination";
import withStyles from "@material-ui/core/es/styles/withStyles";
import {lighten} from '@material-ui/core/styles/colorManipulator';
import Checkbox from "@material-ui/core/Checkbox";
import Tooltip from "@material-ui/core/Tooltip";
import TableSortLabel from "@material-ui/core/TableSortLabel";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";
import IconButton from "@material-ui/core/IconButton";
import DeleteIcon from '@material-ui/icons/Delete';
import FilterListIcon from '@material-ui/icons/FilterList';
import classNames from 'classnames';
import axios from "../../axios/axios";
import CircularProgress from "@material-ui/core/CircularProgress";
import {connect} from "react-redux";
import {toast} from "react-toastify";
import Grid from "@material-ui/core/Grid";
import AddCategoryForm from "./AddCategoryForm";
import EditCategoryModal from "./EditCategoryModal";
import Button from "@material-ui/core/Button";
import SearchBox from "../SearchBox/SearchBox";
import debounce from 'lodash.debounce';
import {withRouter} from "react-router-dom";

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

let counter = 0;

function createData(name, calories, fat, carbs, protein) {
    counter += 1;
    return {id: counter, name, calories, fat, carbs, protein};
}

function desc(a, b, orderBy) {
    if (b[orderBy] < a[orderBy]) {
        return -1;
    }
    if (b[orderBy] > a[orderBy]) {
        return 1;
    }
    return 0;
}

function stableSort(array, cmp) {
    const stabilizedThis = array.map((el, index) => [el, index]);
    stabilizedThis.sort((a, b) => {
        const order = cmp(a[0], b[0]);
        if (order !== 0) return order;
        return a[1] - b[1];
    });
    return stabilizedThis.map(el => el[0]);
}

function getSorting(order, orderBy) {
    return order === 'desc' ? (a, b) => desc(a, b, orderBy) : (a, b) => -desc(a, b, orderBy);
}

const rows = [
    {id: 'id', numeric: false, disablePadding: true, label: 'Id Kategori'},
    {id: 'name', numeric: false, disablePadding: true, label: 'Nazwa Kategori'},
];

class EnhancedTableHead extends React.Component {
    createSortHandler = property => event => {
        this.props.onRequestSort(event, property);
    };

    render() {
        const {onSelectAllClick, order, orderBy, numSelected, rowCount, user} = this.props;

        return (
            <TableHead>
                <TableRow>
                    {user.length !== 0 &&
                    <React.Fragment>
                        {user[0].roles.includes('ROLE_ADMIN') &&
                        <TableCell padding="checkbox">
                            <Checkbox
                                indeterminate={numSelected > 0 && numSelected < rowCount}
                                checked={numSelected === rowCount}
                                onChange={onSelectAllClick}
                            />
                        </TableCell>
                        }
                    </React.Fragment>
                    }
                    {rows.map(
                        row => (
                            <TableCell
                                key={row.id}
                                align={row.numeric ? 'right' : 'left'}
                                padding={'default'}
                                sortDirection={orderBy === row.id ? order : false}
                            >
                                <Tooltip
                                    title="Sort"
                                    placement={row.numeric ? 'bottom-end' : 'bottom-start'}
                                    enterDelay={300}
                                >
                                    <TableSortLabel
                                        active={orderBy === row.id}
                                        direction={order}
                                        onClick={this.createSortHandler(row.id)}
                                    >
                                        {row.label}
                                    </TableSortLabel>
                                </Tooltip>
                            </TableCell>
                        ),
                        this,
                    )}
                    <TableCell padding="default">
                    </TableCell>
                    <TableCell padding="default">
                    </TableCell>
                </TableRow>
            </TableHead>
        );
    }
}

EnhancedTableHead.propTypes = {
    numSelected: PropTypes.number.isRequired,
    onRequestSort: PropTypes.func.isRequired,
    onSelectAllClick: PropTypes.func.isRequired,
    order: PropTypes.string.isRequired,
    orderBy: PropTypes.string.isRequired,
    rowCount: PropTypes.number.isRequired,
};

EnhancedTableHead = connect(mapStateToProps)(EnhancedTableHead);

const toolbarStyles = theme => ({
    root: {
        paddingRight: theme.spacing.unit,
    },
    highlight:
        theme.palette.type === 'light'
            ? {
                color: theme.palette.secondary.main,
                backgroundColor: lighten(theme.palette.secondary.light, 0.85),
            }
            : {
                color: theme.palette.text.primary,
                backgroundColor: theme.palette.secondary.dark,
            },
    spacer: {
        flex: '1 1 100%',
    },
    actions: {
        color: theme.palette.text.secondary,
    },
    title: {
        flex: '0 0 auto',
    },
});

let EnhancedTableToolbar = props => {
    const {numSelected, classes, deleteCategory, query, handleChangeQuery, getAll} = props;

    return (
        <React.Fragment>
            <Toolbar
                className={classNames(classes.root, {
                    [classes.highlight]: numSelected > 0,
                })}
            >
                <div className={classes.title}>
                    {numSelected > 0 ? (
                        <Typography color="inherit" variant="subtitle1">
                            {numSelected} selected
                        </Typography>
                    ) : (
                        <Typography variant="h6" id="tableTitle">
                            Kategorie
                        </Typography>
                    )}
                </div>
                <div className={classes.spacer}/>
                <div className={classes.actions}>
                    {numSelected > 0 ? (
                        <Tooltip title="Delete" onClick={deleteCategory}>
                            <IconButton aria-label="Delete">
                                <DeleteIcon/>
                            </IconButton>
                        </Tooltip>
                    ) : (
                        <Tooltip title="Filter list">
                            <IconButton aria-label="Filter list">
                                <FilterListIcon/>
                            </IconButton>
                        </Tooltip>
                    )}
                </div>
            </Toolbar>
            <SearchBox query={query} handleChangeQuery={handleChangeQuery} getAll={getAll}/>
        </React.Fragment>
    );
};

EnhancedTableToolbar.propTypes = {
    classes: PropTypes.object.isRequired,
    numSelected: PropTypes.number.isRequired,
};

EnhancedTableToolbar = withStyles(toolbarStyles)(EnhancedTableToolbar);

const styles = theme => ({
    root: {
        width: '100%',
        marginTop: theme.spacing.unit * 3,
        margin: 'auto',
    },
    table: {
        minWidth: 1020,
    },
    tableWrapper: {
        overflowX: 'auto',
    },
    paper: {
        margin: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    loginPaper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
});


class Category extends Component {
    constructor(props) {
        super(props);
        this.state = {
            order: 'asc',
            orderBy: 'calories',
            selected: [],
            data: {},
            page: 0,
            rowsPerPage: 5,
            loading: true,
            count: 0,
            query: ''
        };
    }

    componentDidMount = () => {
        this.getAllCategory();
    };

    handleChangeQuery = (e) => {
        const query = e.target.value;
        this.setState({query});
    };

    deleteCategory = () => {
        let data = this.state.data;
        this.state.selected.forEach((item, key) => {
            axios.delete(`/api/v1/category/${item}`, {
                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
            })
                .then((response) => {
                    toast.success(`Kategoria o id: ${item} pomyślnie usunięta`, {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                }).catch((error) => {
                toast.error(`Coś poszło nie tak`, {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            });
            data.splice(key, 1);
        });


        this.setState({
            selected: [],
            data
        });
    };

    getAllCategory = () => {
        this.setState({
            loading: true,
        });
        axios.get(`/api/v1/category?page=${this.state.page + 1}&limit=${this.state.rowsPerPage}&query=${this.state.query}`)
            .then((response) => {
                this.setState({
                    data: response.data.data,
                    loading: false,
                    count: response.data.total
                });
            }).catch((e) => {
            this.setState({
                data: [],
                loading: false,
            });
        })
    };

    handleRequestSort = (event, property) => {
        const orderBy = property;
        let order = 'desc';

        if (this.state.orderBy === property && this.state.order === 'desc') {
            order = 'asc';
        }

        this.setState({order, orderBy});
    };

    handleSelectAllClick = event => {
        if (event.target.checked) {
            this.setState(state => ({selected: state.data.map(n => n.id)}));
            return;
        }
        this.setState({selected: []});
    };

    handleClick = (event, id) => {
        const {selected} = this.state;
        const selectedIndex = selected.indexOf(id);
        let newSelected = [];

        if (selectedIndex === -1) {
            newSelected = newSelected.concat(selected, id);
        } else if (selectedIndex === 0) {
            newSelected = newSelected.concat(selected.slice(1));
        } else if (selectedIndex === selected.length - 1) {
            newSelected = newSelected.concat(selected.slice(0, -1));
        } else if (selectedIndex > 0) {
            newSelected = newSelected.concat(
                selected.slice(0, selectedIndex),
                selected.slice(selectedIndex + 1),
            );
        }

        this.setState({selected: newSelected});
    };

    handleChangePage = (event, page) => {
        console.log(page);
        this.setState({page}, () => {
            this.getAllCategory();
        });
    };

    handleChangeRowsPerPage = event => {
        this.setState({rowsPerPage: event.target.value}, () => {
            this.getAllCategory();
        });
    };

    isSelected = id => this.state.selected.indexOf(id) !== -1;

    render() {
        const {classes} = this.props;
        const {data, order, orderBy, selected, rowsPerPage, page, count} = this.state;
        const emptyRows = rowsPerPage - Math.min(rowsPerPage, data.length - page * rowsPerPage);
        if (this.state.loading === true) {
            return (
                <Paper className={classes.paper}>
                    <CircularProgress color="secondary"/>
                </Paper>
            );
        }

        return (
            <Grid container className={classes.root} spacing={24}>
                <Grid item xs={12} md={8}>
                    <Paper>
                        <EnhancedTableToolbar numSelected={selected.length} deleteCategory={this.deleteCategory}
                                              query={this.state.query} handleChangeQuery={this.handleChangeQuery}
                                              getAll={this.getAllCategory}
                        />
                        <div className={classes.tableWrapper}>
                            <Table className={classes.table} aria-labelledby="tableTitle">
                                <EnhancedTableHead
                                    numSelected={selected.length}
                                    order={order}
                                    orderBy={orderBy}
                                    onSelectAllClick={this.handleSelectAllClick}
                                    onRequestSort={this.handleRequestSort}
                                    rowCount={data.length}
                                />
                                <TableBody>
                                    {this.state.data
                                        .map(n => {
                                            const isSelected = this.isSelected(n.id);
                                            return (
                                                <TableRow
                                                    hover
                                                    role="checkbox"
                                                    aria-checked={isSelected}
                                                    tabIndex={-1}
                                                    key={n.id}
                                                    selected={isSelected}
                                                >
                                                    {this.props.user.length !== 0 &&
                                                    <React.Fragment>
                                                        {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                                                        <TableCell padding="checkbox"
                                                                   onClick={event => this.handleClick(event, n.id)}>
                                                            <Checkbox checked={isSelected}/>
                                                        </TableCell>
                                                        }
                                                    </React.Fragment>
                                                    }
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {n.id}
                                                    </TableCell>
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {n.name}
                                                    </TableCell>
                                                    <TableCell>
                                                        <Button
                                                            variant="contained"
                                                            color="primary"
                                                            type="submit"
                                                            onClick={() => {
                                                                this.props.history.push(`/kategorie/${n.id}/posty`);
                                                            }}
                                                        >
                                                            Zobacz posty
                                                        </Button>
                                                    </TableCell>
                                                    <TableCell component="th" scope="row" padding="default">
                                                        {this.props.user.length !== 0 &&
                                                        <React.Fragment>
                                                            {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                                                            <React.Fragment>
                                                                <EditCategoryModal category={n}
                                                                                   getCategory={this.getAllCategory}/>
                                                            </React.Fragment>
                                                            }
                                                        </React.Fragment>
                                                        }
                                                    </TableCell>
                                                </TableRow>
                                            );
                                        })}
                                    {emptyRows > 0 && (
                                        <TableRow>
                                            <TableCell colSpan={6}/>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                        <TablePagination
                            rowsPerPageOptions={[5, 10, 25]}
                            component="div"
                            count={count}
                            rowsPerPage={rowsPerPage}
                            page={page}
                            backIconButtonProps={{
                                'aria-label': 'Poprzednia strona',
                            }}
                            nextIconButtonProps={{
                                'aria-label': 'Nastepna strona',
                            }}
                            onChangePage={this.handleChangePage}
                            onChangeRowsPerPage={this.handleChangeRowsPerPage}
                        />
                    </Paper>
                </Grid>
                {this.props.user.length !== 0 &&
                <Grid item xs={12} md={4}>
                    {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                    <Paper className={classes.loginPaper}>
                        <AddCategoryForm getCategory={this.getAllCategory}/>
                    </Paper>
                    }
                </Grid>
                }
            </Grid>
        );
    }
}

Category.propTypes = {};

export default withRouter(connect(mapStateToProps)(withStyles(styles)(Category)));